<?php
namespace Adapt\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Fabsor\DrupalReleaseApi\HTTPReleaseFetcher;

/**
 * Command for creating Adapt client dirs.
 */
class CreateClientDirCommand extends BaseCommand
{

  var $twig;
  var $gituri;

  protected function configure()
  {
    $this->setName('create-client-dir')
       ->setDescription('Create a Adapt project')
       ->addArgument('name', InputArgument::REQUIRED, 'The client-dir name')
       ->addOption('title', null, InputOption::VALUE_OPTIONAL, 'The title of the client-dir')
       ->addOption('description', null, InputOption::VALUE_OPTIONAL, 'The description of the client-dir')
       ->addOption('remote-git', null, InputOption::VALUE_NONE, "Initialize remote git repository")
       ->addOption('domain', 'd', InputOption::VALUE_OPTIONAL, "The full domain of the site e.g. example.com");
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $config = json_decode(file_get_contents(__DIR__ . '../../../../config.json'));

    $tmp_path = '/tmp/' . uniqid();

    $name = $input->getArgument('name');
    $profile = $name;

    $dialog = $this->getHelperSet()->get('dialog');
    $title = $input->getOption('title');
    $description = $input->getOption('description');
    $domain = $input->getOption('domain');

    if (empty($title)) {
      $title = $dialog->ask($output, '<question>Enter the title of the client dir:</question> ');
    }

    if (empty($title)) {
      $title = $name;
    }

    if (empty($description)) {
      $description = $dialog->ask($output, '<question>Enter the description of the client dir</question>');
    }

    if (empty($description)) {
      $description = $name;
    }

    if (empty($domain)) {
      $domain = $dialog->ask($output, '<question>Enter the domain of the site</question>');
    }

    if (empty($domain)) {
      $domain = $name . '.dk';
    }

    $gituri = 'file://' . $config->git->local;
    $remote_git = $input->getOption('remote-git');
    if ($remote_git) {
      $gituri = $config->git->remote;
    }

    $this->twig = $this->getTwig();

    $fetcher = new HTTPReleaseFetcher();
    $release = $fetcher->getReleaseInfo('drupal', '7.x')->getCurrentRelease();
    $drupal_core_version = "{$release['major']}.{$release['patch']}";

    $platform_path = "$tmp_path/platform";
    $profile_path = "$tmp_path/profile";
    //$theme_path = "$profile_path/themes/custom/";

    $projects = array();
    $dependencies = array();

    $fetcher = new HTTPReleaseFetcher();

    // Build array with projects to be downloaded and their dependencies
    foreach ($config->modules->default->projects as $project) {
      $release = $fetcher->getReleaseInfo($project->name, '7.x')->getCurrentRelease();
      $version = "{$release['major']}.{$release['patch']}";
      if (!empty($release['extra'])) {
          $version .= "-{$release['extra']}";
      }

      $projects[$project->name] = array(
        'name' => $project->name,
        'type' => 'module',
        'version' => $version,
        'subdir' => 'contrib',
      );
    }

    $dependencies = $config->modules->default;

    $variables = array(
      'drupal_core_version' => $drupal_core_version,
      'name' => $name,
      'domain' => $domain,
      'gituri' => $gituri,
      'profile' => $profile,
      'title' => $title,
      'description' => $description,
      'projects' => $projects,
      'dependencies' => $dependencies,
      'cron_key' => $this->generate_password('cron'),
      'admin_name' => 'adaptadmin',
      'admin_password' => $this->generate_password(),
    );

    // Create tmp folder for client dir
    mkdir($tmp_path);

    // Generate platform files and commit to git
    $this->generate_platform($platform_path, $output, $variables, $config);
    $this->git_init($gituri, $name . '/platform', $platform_path, $output);

    // Generate profile files and commit to git
    $this->generate_profile($profile_path, $output, $variables, $config);
    $this->generate_theme($profile_path, $output, $variables, $config);
    $this->git_init($gituri, $profile . '/profile', $profile_path, $output);

    // Generate theme files and commit to git
    // $this->generate_theme($theme_path, $output, $variables);
    // $this->git_init($gituri, $theme, $theme_path, $output);

    // Cleanup
    $this->executeExternalCommand("rm -fr $tmp_path", $output);

    $output->writeln(
      "<info>Succeeded, now make a local clone: git clone ${gituri}/${name}/platform.git $name </info>"
    );
  }

  protected function git_init($gituri, $repo, $path, $output)
  {
    $this->executeExternalCommand("cd $path; git init", $output);
    $this->executeExternalCommand("cd $path; git add .", $output);
    $this->executeExternalCommand("cd $path; git commit -m 'initial commit'", $output);
    $this->executeExternalCommand("cd $path; git remote add origin $gituri/$repo.git", $output);
    $this->executeExternalCommand("cd $path; git push origin master", $output);
  }

  protected function generate_password($prefix = false, $length = 20)
  {
    $password = substr(str_shuffle(sha1(microtime())), 0, $length);
    return $prefix ? "$prefix-$password" : $password;
  }

  /**
   * Generate the basic platform structure
   *
   * @param $path
   * @param OutputInterface $output
   * @param $variables
   */
  protected function generate_platform($path, OutputInterface $output, $variables, $config)
  {
    $domain = $variables['domain'];
    $name = $variables['name'];
    $domains = array(
      'live' => $domain,
      'stage' => $config->domains->stage_prefix . $name . $config->domains->stage_suffix,
      'local' => $config->domains->local_prefix . $domain . $config->domains->local_suffix,
    );

    $aliases = array(
      'name' => $name,
      'domain' => $domains,
      'htdocs' => array(
        'live' => "/home/drupal/{$name}.live/site/htdocs",
        'stage' => "/home/drupal/{$name}.stage/site/htdocs",
      ),
      'ssh-host' => array(
        'live' => "",
        'stage' => "local.salvia",
      )
    );

    mkdir($path);
    file_put_contents("$path/.gitignore", $this->twig->render('platform/gitignore'));
    file_put_contents("$path/platform.make", $this->twig->render('platform/platform.make', $variables));
    file_put_contents("$path/build.sh", $this->twig->render('platform/build.sh', $variables));
    $this->executeExternalCommand("chmod +x $path/build.sh", $output);
    file_put_contents("$path/install.sh", $this->twig->render('platform/install.sh', $variables));
    $this->executeExternalCommand("chmod +x $path/install.sh", $output);

    $drush = $path . "/drush";
    mkdir($drush);
    // root drush directory
    file_put_contents("$drush/README.md", $this->twig->render('platform/drush/README.md'));
    file_put_contents("$drush/drushrc.php", $this->twig->render('platform/drush/drushrc.php'));
    // aliases
    mkdir($drush . "/aliases");
    file_put_contents("$drush/aliases/aliases.drushrc.php", $this->twig->render('platform/drush/aliases/aliases.drushrc.php', $aliases));
    // Commands
    mkdir($drush . "/commands");
    $cmds = array('build', 'downsync', 'englishdevel', 'policy', 'registry_rebuild');
    foreach ($cmds as $cmd) {
      $cmd = $cmd . '.drush.inc';
      file_put_contents("$drush/commands/$cmd", $this->twig->render("platform/drush/commands/{$cmd}"));
    }

    mkdir($path . '/settings');

    $config = array(
      'clientdir'  => $variables['profile'],
      'domain' => $domain,
      'cronkey'    => $variables['cron_key'],
      'adaptadminpass' => $variables['admin_password'],
    );

    foreach (array('local', 'test', 'prod') as $env) {
      $settings = array(
        'profile' => $variables['profile'],
        'database' => "{$variables['name']}_{$env}",
        'username' => ($env == 'local' ? 'root' : "{$variables['name']}_{$env}"),
        'password' => ($env == 'local' ? 'root' : $this->generate_password()),
        'hostname' => ($env == 'local' ? '127.0.0.1' : "{$name}.mysql.{$env}.cd.adapt.dk"),
        'env'      => $env,
        'domains' => $domains,
        'prime' => array(
          'tgt' => 'https://prime01/sitereporting/adapt_monitor/report',
          'ss' => '',
          'enabled' => ($env != 'loval') ? TRUE : FALSE,
          'key' => "{$variables['name']}_{$env}",
        ),
      );

      file_put_contents(
        "$path/settings/{$env}.settings.php",
        $this->twig->render('platform/settings.php', $settings)
      );

      if ($env == 'local') {
          file_put_contents("$path/local_setup.sh", $this->twig->render('platform/local_setup.sh', $settings));
          $this->executeExternalCommand("chmod +x $path/local_setup.sh", $output);
      } else {
        $config["mysqlpass{$env}"] = $settings['password'];
        $config["htaccesspass{$env}"] = $this->generate_password('', 10);
      }
    }

    // Write config file used to setup apache/cron stuff.
    file_put_contents("$path/settings/config", $this->twig->render('platform/config', $config));

  }

  /**
   * Generate the profile structure
   *
   * @param $path
   * @param OutputInterface $output
   * @param $variables
   */
  protected function generate_profile($path, OutputInterface $output, $variables, $config)
  {
    $profile = $variables['profile'];
    mkdir($path);

    mkdir("$path/modules");
    $this->executeExternalCommand("touch $path/modules/.gitignore", $output);

    mkdir("$path/modules/custom");
    $this->executeExternalCommand("touch $path/modules/custom/.gitignore", $output);

    mkdir("$path/themes");
    $this->executeExternalCommand("touch $path/themes/.gitignore", $output);

    file_put_contents("$path/.gitignore", $this->twig->render('profile/gitignore', $variables));
    file_put_contents("$path/$profile.profile", $this->twig->render('profile/profile.profile', $variables));
    file_put_contents("$path/$profile.install", $this->twig->render('profile/profile.install', $variables));
    file_put_contents("$path/$profile.info", $this->twig->render('profile/profile.info', $variables));
    file_put_contents("$path/$profile.make", $this->twig->render('profile/profile.make', $variables));
  
  }

  /**
   * Generate the theme
   *
   * @param $path
   * @param OutputInterface $output
   * @param $variables
   */
  protected function generate_theme($path, OutputInterface $output, $variables, $config)
  {
    $template_path = $this->getTwig()->getLoader()->getPaths();
    $template_path = $template_path[0];
    $profile = $variables['profile'];

    $theme_path = "$path/themes/custom/{$profile}_theme";

    mkdir($theme_path,0775,TRUE);

    $this->executeExternalCommand("cp -r $template_path/theme/ $theme_path", $output);
    $this->executeExternalCommand("mv $theme_path/gitignore $theme_path/.gitignore", $output);

    file_put_contents("$theme_path/{$profile}_theme.info", $this->twig->render("theme/theme.info", $variables));
    file_put_contents("$theme_path/.npm/build-favicons.js", $this->twig->render("theme/.npm/build-favicons.js", $variables));
    $this->executeExternalCommand("rm $theme_path/theme.info", $output);

  }
}
