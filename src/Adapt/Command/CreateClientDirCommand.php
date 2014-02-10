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
    protected function configure()
    {
        $this->setName('create-client-dir')
            ->setDescription('Create a Adapt project')
            ->addArgument('name', InputArgument::REQUIRED, 'The client-dir name')
            ->addOption('title', null, InputOption::VALUE_NONE, 'The title of the client-dir')
            ->addOption('description', null, InputOption::VALUE_NONE, 'The description of the client-dir')
            ->addOption('remote-git', null, InputOption::VALUE_NONE, "Initialize remote git repository");

    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $config = json_decode(file_get_contents(__DIR__ . '../../../../config.json'));
        
        $gituri = 'file://' . $config->git->local;
        $remote_git = $input->getOption('remote-git');
        $tmp_path = '/tmp/' . uniqid();

        if ($remote_git) {
          $gituri = $config->git->remote;
        }

        $name = $input->getArgument('name');
        $profile = $name . '_profile';
        
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
        
        $twig = $this->getTwig();
        
        $fetcher = new HTTPReleaseFetcher();
        $release = $fetcher->getReleaseInfo('drupal', '7.x')->getCurrentRelease();
        $drupal_core_version = "{$release['major']}.{$release['patch']}";

        $profile_path  = "$tmp_path/profile";
        $platform_path = "$tmp_path/platform";

        $projects = array();
        $dependencies = array();  
                       
        $fetcher = new HTTPReleaseFetcher();
        
        // Build array with projects to be downloaded and their dependencies
        foreach ($config->modules->default->projects as $project ) {
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
          'gituri' => $gituri,
          'profile' => $profile, 
          'title' => $title, 
          'description' => $description, 
          'projects' => $projects,
          'dependencies' => $dependencies,
          'cron_key' => uniqid('cron-',TRUE),
        );

        // Create tmp folder for client dir
        mkdir($tmp_path);
        
        // Generate platform files and commit to git
        mkdir($platform_path);
        file_put_contents("$platform_path/.gitignore", $twig->render('gitignore', $variables));
        
        $site_path = "$platform_path/htdocs/sites/default/";
        mkdir($site_path,0775,TRUE);
        
        foreach (array('local','dev','test','live') as $env) { 
          $settings = array(
            'profile' => $profile,
            'database' => "${name}_${env}",
            'username' => "${name}_${env}",
            'password' => uniqid('pw-',TRUE),
            'hostname' => ($env == 'local' ? 'localhost' : 'some_server'),
          );
          file_put_contents("$site_path/{$env}.settings.php", $twig->render('settings.php', $settings));
          if ($env == 'local') {
            file_put_contents("$platform_path/local_setup.sh", $twig->render('local_setup.sh', $settings));
            $this->executeExternalCommand("chmod +x $platform_path/local_setup.sh", $output);
          }
        }
 
        file_put_contents("$platform_path/platform.make", $twig->render('platform/platform.make', $variables));
        file_put_contents("$platform_path/build.sh", $twig->render('platform/build.sh', $variables));
        $this->executeExternalCommand("chmod +x $platform_path/build.sh", $output);
        file_put_contents("$platform_path/install.sh", $twig->render('platform/install.sh', $variables));
        $this->executeExternalCommand("chmod +x $platform_path/install.sh", $output);
        $this->git_init($gituri, $name . '_platform', $platform_path, $output);
        
        // Generate profile files and commit to git         
        mkdir($profile_path);
        mkdir("$profile_path/includes");
        file_put_contents("$profile_path/.gitignore", $twig->render('profile/gitignore', $variables));
        file_put_contents("$profile_path/$profile.profile", $twig->render('profile/profile.profile', $variables));
        file_put_contents("$profile_path/$profile.install", $twig->render('profile/profile.install', $variables));
        file_put_contents("$profile_path/$profile.info", $twig->render('profile/profile.info', $variables));
        file_put_contents("$profile_path/$profile.make", $twig->render('profile/profile.make', $variables));
        file_put_contents("$profile_path/includes/settings.php", $twig->render('profile/includes/settings.php', $variables));
        $this->git_init($gituri, $profile, $profile_path, $output);

        $output->writeln("<info>Succeeded, now make a local clone: git clone ${gituri}/${name}_platform.git $name </info>");
           
    }
        
    function git_init($gituri, $repo, $path, $output) {
      $this->executeExternalCommand("cd $path; git init", $output);
      $this->executeExternalCommand("cd $path; git add .", $output);
      $this->executeExternalCommand("cd $path; git commit -m 'initial commit'", $output);
      $this->executeExternalCommand("cd $path; git remote add origin $gituri/$repo.git", $output);
      $this->executeExternalCommand("cd $path; git push origin master", $output);
    }
    
    
}