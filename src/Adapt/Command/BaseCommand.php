<?php

namespace Adapt\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * The base command class is an abstract class with some helper methods.
 */
abstract class BaseCommand extends Command
{
    /**
     * Execute an external command and print anything using the provided
     * output class.
     * @param string $command
     * @param OutputInterface $output
     * @throws \Exception if the command fails.
     */
    protected function executeExternalCommand($command, OutputInterface $output)
    {
        $process = new Process($command);
        $process->run(function ($type, $buffer) use ($output) {
            $output->writeln($buffer);
        });
        if (!$process->isSuccessful()) {
            throw new \Exception("Command $command failed");
        }
    }

    /**
     * Get a twig environment.
     * @return \Twig_Environment
     *   A twig environment that points to the appropriate template directory.
     */
    protected function getTwig()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../../templates');
        $twig = new \Twig_Environment($loader, array());
        return $twig;
    }
}