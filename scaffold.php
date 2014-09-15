#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Adapt\Command\CreateClientDirCommand;
use Adapt\Command\CreateLocalRepoCommand;

/**
 * <comment> is yellow text
 * <info> is green
 */

$update_info = "\r\n\r\n<question>Did you remember to Update?</question>";
$update_info .= "\r\n<comment>You can run:</comment> \"<info>cd " . dirname(__FILE__) . "; git pull</info>\" <comment>to update</comment>";

$application = new Application("Adapt scaffold", "2.0{$update_info}");
$application->add(new CreateClientDirCommand);
$application->add(new CreateLocalRepoCommand);

$application->run();
