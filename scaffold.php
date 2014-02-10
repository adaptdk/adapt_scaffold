#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Adapt\Command\CreateClientDirCommand;

$application = new Application("Adapt scaffold", "2.0");
$application->add(new CreateClientDirCommand);
$application->run();