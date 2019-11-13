<?php

namespace App\Command;

use App\Component\Console\Command\Command;
use App\GroupCalculator\GroupCalculatorInterface;
use App\GroupCalculator\Model\DataObject;
use App\GroupCalculator\Reader\Reader;
use App\GroupCalculator\Writer\SteamWriter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCommand extends Command
{
    protected static $defaultName = 'app:parser';
    private $clearCache = true;

    /**
     * @var string
     */
    private $storageDir;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var SteamWriter
     */
    private $writer;

    /**
     * @var GroupCalculatorInterface
     */
    private $groupCalculator;

    public function __construct(
        string $storageDir,
        Reader $reader,
        SteamWriter $writer,
        GroupCalculatorInterface $groupCalculator
    )
    {
        $this->storageDir = $storageDir;
        $this->reader = $reader;
        $this->writer = $writer;
        $this->groupCalculator = $groupCalculator;

        parent::__construct();
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
        $csvStoragePath = $input->getArgument('path') ?? $this->storageDir . '/data';
        $resultFilePath = $input->getArgument('result_file') ?? $this->storageDir . '/result.csv';

        $this->clearCache($output);

        //Parser csv
        $this->parseCSV($output, $csvStoragePath);

        //Save result
        $this->saveResult($output, $resultFilePath);

        $this->clearCache($output);

        $output->writeln([
            '',
            'Done',
        ]);
    }

    private function clearCache(OutputInterface $output)
    {
        if($this->clearCache){
            $output->writeln([
                '',
                'Clear cache',
                '============',
                '',
            ]);
            $this->groupCalculator->clearCache();
        }
    }

    private function parseCSV(OutputInterface $output, string $csvStoragePath)
    {
        $output->writeln([
            '',
            'Start parse',
            '============',
            '',
        ]);

        $progressBar = $this->initProgressBar($output);
        $progressBar->start();

        foreach ($this->reader->getData($csvStoragePath) as $data) {
            $this->groupCalculator->addition($data);
            $progressBar->advance();
        }

        $progressBar->finish();
    }

    private function saveResult(OutputInterface $output, string $resultFilePath)
    {
        $output->writeln([
            '',
            'Save result',
            '============',
            '',
        ]);

        $progressBar = $this->initProgressBar($output);
        $progressBar->start();

        $this->writer->add($resultFilePath, ['date', 'A', 'B', 'C']);

        /**
         * @var DataObject $row
         */
        foreach ($this->groupCalculator->getResult() as $row) {
            $this->writer->add($resultFilePath, $row->toArray());
            $progressBar->advance();
        }

        $progressBar->finish();
    }
}