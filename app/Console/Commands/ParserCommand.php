<?php

namespace App\Console\Commands;

use App\Cache\Adapter\FilesystemAdapter;
use App\Component\Console\Command\Command;
use App\GroupCalculator\Cache\CacheAdapter;
use App\GroupCalculator\GroupCalculator;
use App\GroupCalculator\Model\DataObject;
use App\GroupCalculator\Reader\CSVReader;
use App\GroupCalculator\Writer\CSVSteamWriter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCommand extends Command
{
    protected static $defaultName = 'app:parser';
    private $clearCache = false;

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->addArgument('path', InputArgument::OPTIONAL, 'CSV storage path')
            ->addArgument('result_file', InputArgument::OPTIONAL, 'Result file path')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Init
        $csvStoragePath = $input->getArgument('path') ?? PROJECT_ROOT . '/storage/data';
        $resultFilePath = $input->getArgument('result_file') ?? PROJECT_ROOT . '/storage/result.csv';

        $reader = new CSVReader($csvStoragePath);
        $writer = new CSVSteamWriter($resultFilePath);

        $cacheAdapter = new CacheAdapter(
            new FilesystemAdapter('groupCalculator', 0, PROJECT_ROOT . '/storage/cache')
        );
        if($this->clearCache){
            $cacheAdapter->clear();
        }

        $calc = new GroupCalculator($cacheAdapter);

        //Parser csv
        $this->parseCSV($output, $reader, $calc);

        //Save result
        $this->saveResult($output, $writer, $calc);

        if($this->clearCache){
            $cacheAdapter->clear();
        }

        $output->writeln([
            '',
            'Done',
        ]);
    }

    private function parseCSV(OutputInterface $output, CSVReader $reader, GroupCalculator $calc)
    {
        $output->writeln([
            '',
            'Start parse',
            '============',
            '',
        ]);

        $progressBar = $this->initProgressBar($output);
        $progressBar->start();

        foreach ($reader->getData() as $data) {
            $calc->addition($data);
            $progressBar->advance();
        }

        $progressBar->finish();
    }

    private function saveResult(OutputInterface $output, CSVSteamWriter $writer, GroupCalculator $calc)
    {
        $output->writeln([
            '',
            'Save result',
            '============',
            '',
        ]);

        $progressBar = $this->initProgressBar($output);
        $progressBar->start();

        $writer->add(['date', 'A', 'B', 'C']);

        /**
         * @var DataObject $row
         */
        foreach ($calc->getResult() as $row) {
            $writer->add($row->toArray());
            $progressBar->advance();
        }

        $progressBar->finish();
    }
}