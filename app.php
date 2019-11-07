<?php

require __DIR__.'/vendor/autoload.php';

define('PROJECT_ROOT', __DIR__);

use App\Console\Commands\ParserCommand;
use Symfony\Component\Console\Application;

$application = new Application();

//register commands
$application->add(new ParserCommand());

$application->run();