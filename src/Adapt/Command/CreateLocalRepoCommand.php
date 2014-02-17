<?php
namespace Adapt\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for creating a local repository.
 */
class CreateLocalRepoCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('create-local-repository')
            ->setDescription('Create a local repositories for project')
            ->addArgument('name', InputArgument::REQUIRED, 'The client-dir name');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {

      $config = json_decode(file_get_contents(__DIR__ . '../../../../config.json'));
      
      $name     = $input->getArgument('name');
      $gitpath  = $config->git->local;
      $platform = "{$gitpath}/{$name}_platform.git";
      $profile  = "{$gitpath}/{$name}.git";
      
      if (is_dir($platform) || is_dir($profile)) {
        throw new \Exception("Local repository with name {$name} already exists.");
      }
      
      mkdir($platform);
      $this->executeExternalCommand("cd $platform; git --bare init", $output);
      
      mkdir($profile);
      $this->executeExternalCommand("cd $profile; git --bare init", $output);
      
      $output->writeln("<info>Succeeded, local repositories created </info>");
         
    }    
}