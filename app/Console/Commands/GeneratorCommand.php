<?php

namespace App\Console\Commands;

use App\GroupCalculator\Model\DataObject;
use App\GroupCalculator\Writer\CSVSteamWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class GeneratorCommand extends Command
{
    protected static $defaultName = 'app:generator';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = new \DateTimeImmutable('1980-01-01');
        $daysLimit = 10000;
        $filesLimit = 1000;

        $progressBar = new ProgressBar($output, $filesLimit);

        $progressBar->start();

        for ($i = 0; $i < $filesLimit; $i++) {
            $generator = $this->generator($date, $daysLimit);
            $this->generateFile(PROJECT_ROOT . "/storage/data/test{$i}.csv", $generator);

            $progressBar->advance();
        }

        $progressBar->finish();

        $output->writeln('');
        $output->writeln('DONE');
    }

    private function generator(\DateTimeImmutable $startDate, int $limit): \Generator
    {
        for ($i = 0; $i < $limit; $i++) {
            $date = $startDate->modify("+$i days")->format('Y-m-d');
            $param1 = mt_rand (-100 , 100);
            $param2 = mt_rand (-100 , 100);
            $param3 = mt_rand (-100 , 100);

            yield new DataObject($date, $param1, $param2, $param3);
        }
    }

    private function generateFile($path, \Generator $generator)
    {
        $writer = new CSVSteamWriter($path);

        $writer->setHeader([
            'date',
            'A',
            'B',
            'C'
        ]);

        foreach ($generator as $row) {
            $writer->add($row);
        }
    }
}