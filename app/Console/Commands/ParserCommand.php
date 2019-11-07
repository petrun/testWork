<?php

namespace App\Console\Commands;

use App\GroupCalculator\Cache\ArrayCache;
use App\GroupCalculator\Cache\CacheAdapter;
use App\GroupCalculator\Cache\FileCache;
use App\GroupCalculator\GroupCalculator;
use App\GroupCalculator\Reader\CSVReader;
use App\GroupCalculator\Writer\CSVSteamWriter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ParserCommand extends Command
{
    protected static $defaultName = 'app:parser';

//    private $directoryPath;

    public function __construct()
    {
//        // best practices recommend to call the parent constructor first and
//        // then set your own properties. That wouldn't work in this case
//        // because configure() needs the properties set in this constructor
////        $this->directoryPath = $directoryPath;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // ...
            ->addArgument('path', InputArgument::OPTIONAL, 'Directory path')//            ->addArgument('last_name', InputArgument::OPTIONAL, 'Your last name?')
        ;

//        $this
//            ->addArgument(
//                'path',
//                InputArgument::REQUIRED,
////                $this->directoryPath ? InputArgument::REQUIRED : InputArgument::OPTIONAL,
//                'Directory path'
//            )
//        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        dump($path);

        $reader = new CSVReader(PROJECT_ROOT . '/storage/data');
        $writer = new CSVSteamWriter(PROJECT_ROOT . '/storage/result.csv');

        $cacheWrapper = new FilesystemAdapter('groupCalculator', 0, PROJECT_ROOT . '/storage/cache');
//        $cache->clear();

//        $cache = new FileCache(PROJECT_ROOT . '/storage/cache');
//        $cache = new ArrayCache();

        $cache = new CacheAdapter($cacheWrapper);
        $calc = new GroupCalculator($cache);


        $output->writeln('Start parse:');

        $progressBar = $this->initProgressBar($output);
        $progressBar->start();

//        foreach ($reader->getData() as $data) {
//            $calc->addition($data);
//            $progressBar->advance();
//        }

        $progressBar->finish();

        $output->writeln('');
        $output->writeln('Save result:');

        $progressBar = $this->initProgressBar($output);
        $progressBar->start();

        foreach ($calc->getResult() as $row) {
            $writer->add($row);
            $progressBar->advance();
        }

        $progressBar->finish();

//        $cache->clear();

        $output->writeln('');
        $output->writeln('DONE');
    }

    private function initProgressBar(OutputInterface $output, int $max = 0): ProgressBar
    {
        $progressBar = new ProgressBar($output, $max);

        $progressBar->setFormat(
            "%current% [%bar%]\n %memory:6s%"
        );
        return $progressBar;
    }
}