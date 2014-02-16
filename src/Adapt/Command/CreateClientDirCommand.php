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
class CreateClientDirCommand extends BaseCommand {

  var $twig;
  var $gituri;

  protected function configure() {
    $this->setName('create-client-dir')
         ->setDescription('Create a Adapt project')
         ->addArgument('name', InputArgument::REQUIRED, 'The client-dir name')
         ->addOption('title', NULL, InputOption::VALUE_OPTIONAL, 'The title of the client-dir')
         ->addOption('description', NULL, InputOption::VALUE_OPTIONAL, 'The description of the client-dir')
         ->addOption('remote-git', NULL, InputOption::VALUE_NONE, "Initialize remote git repository");
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $config = json_decode(file_get_contents(__DIR__ . '../../../../config.json'));

    $tmp_path = '/tmp/' . uniqid();

    $name = $input->getArgument('name');
    $profile = $name;
    $theme = $name . '_theme';

    //if (is_dir($name)) {
    //  throw new \Exception("Directory with name {$name} already exists.");
    //}

    $dialog = $this->getHelperSet()->get('dialog');
    $title = $input->getOption('title');
    $description = $input->getOption('description');

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
    $theme_path = "$tmp_path/theme";

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
    $this->generate_platform($platform_path, $output, $variables);
    $this->git_init($gituri, $name . '_platform', $platform_path, $output);

    // Generate profile files and commit to git
    $this->generate_profile($profile_path, $output, $variables);
    $this->git_init($gituri, $profile, $profile_path, $output);

    // Generate theme files and commit to git
    $this->generate_theme($theme_path, $output, $variables);
    $this->git_init($gituri, $theme, $theme_path, $output);

    // Cleanup
    $this->executeExternalCommand("rm -fr $tmp_path", $output);

    $output->writeln("<info>Succeeded, now make a local clone: git clone ${gituri}/${name}_platform.git $name </info>");
  }

  protected function git_init($gituri, $repo, $path, $output) {
    $this->executeExternalCommand("cd $path; git init", $output);
    $this->executeExternalCommand("cd $path; git add .", $output);
    $this->executeExternalCommand("cd $path; git commit -m 'initial commit'", $output);
    $this->executeExternalCommand("cd $path; git remote add origin $gituri/$repo.git", $output);
    $this->executeExternalCommand("cd $path; git push origin master", $output);
  }

  protected function generate_password($prefix = FALSE, $length = 20) {
    $chars = "abcdefghjkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ23456789";
    $password = substr(str_shuffle($chars), 0, $length);
    return $prefix ? "$prefix-$password" : $password;
  }

  /**
   * Generate the basic platform structure
   *
   * @param $path
   * @param OutputInterface $output
   * @param $variables
   */
  protected function generate_platform($path, OutputInterface $output, $variables) {
    mkdir($path);
    file_put_contents("$path/.gitignore", $this->twig->render('platform/gitignore'));
    file_put_contents("$path/platform.make", $this->twig->render('platform/platform.make', $variables));
    file_put_contents("$path/build.sh", $this->twig->render('platform/build.sh', $variables));
    $this->executeExternalCommand("chmod +x $path/build.sh", $output);
    file_put_contents("$path/install.sh", $this->twig->render('platform/install.sh', $variables));
    $this->executeExternalCommand("chmod +x $path/install.sh", $output);

    $site_path = "$path/htdocs/sites/default/";
    mkdir($site_path, 0775, TRUE);

    foreach (array('local', 'dev', 'test', 'live') as $env) {
      $settings = array(
        'profile' => $variables['profile'],
        'database' => "{$variables['name']}_{$env}",
        'username' => "{$variables['name']}_{$env}",
        'password' => $this->generate_password('pw'),
        'hostname' => ($env == 'local' ? 'localhost' : 'some_server'),
      );
      file_put_contents("$site_path/{$env}.settings.php", $this->twig->render('platform/settings.php', $settings));
      if ($env == 'local') {
        file_put_contents("$path/local_setup.sh", $this->twig->render('platform/local_setup.sh', $settings));
        $this->executeExternalCommand("chmod +x $path/local_setup.sh", $output);
      }
    }
  }

  /**
   * Generate the profile structure
   *
   * @param $path
   * @param OutputInterface $output
   * @param $variables
   */
  protected function generate_profile($path, OutputInterface $output, $variables) {
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
  protected function generate_theme($path, OutputInterface $output, $variables) {
    $template_path = $this->getTwig()->getLoader()->getPaths();
    $template_path = $template_path[0];
    $profile = $variables['profile'];
    mkdir($path);
    $this->executeExternalCommand("cp -r $template_path/theme/* $path", $output);
    // The .gitignore file is named gitignore to make sure it's not active in the scaffold repo
    $this->executeExternalCommand("mv $path/gitignore $path/.gitignore", $output);

    file_put_contents("$path/{$profile}_theme.info", $this->twig->render("theme/theme.info", $variables));
    $this->executeExternalCommand("rm $path/theme.info", $output);

  }
}
