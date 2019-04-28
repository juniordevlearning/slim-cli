<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\CreateControllerCommand;

// load configuration array
$config = require __DIR__.'./slim3-cli-config.php';

$application = new Application();

$application->add(new CreateControllerCommand($config));

$application->run();