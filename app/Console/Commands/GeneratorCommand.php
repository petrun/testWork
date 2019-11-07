<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class GeneratorCommand extends Command
{
    protected static $defaultName = 'app:generator';

//    protected function configure()
//    {
//        $this
//            ->addArgument(
//                'path',
//                InputArgument::REQUIRED,
////                $this->directoryPath ? InputArgument::REQUIRED : InputArgument::OPTIONAL,
//                'Directory path'
//            )
//        ;
//    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}