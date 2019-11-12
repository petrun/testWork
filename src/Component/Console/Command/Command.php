<?php

namespace App\Component\Console\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    protected function initProgressBar(OutputInterface $output, int $max = 0): ProgressBar
    {
        $progressBar = new ProgressBar($output, $max);

        $progressBar->setFormat(
            "%current% [%bar%]\n %memory:6s%"
        );
        return $progressBar;
    }
}