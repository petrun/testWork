<?php

namespace App\Command;

use App\Cache\Adapter\FilesystemAdapter;
use App\Component\Console\Command\Command;
use App\GroupCalculator\Cache\CacheAdapter;
use App\GroupCalculator\GroupCalculator;
use App\GroupCalculator\Model\DataObject;
use App\GroupCalculator\Reader\Reader;
use App\GroupCalculator\Writer\SteamWriter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ParserCommand extends Command
{
    protected static $defaultName = 'app:parser';
    private $clearCache = true;

    /**
     * @var string $rootPath
     */
    private $rootPath;

    /**
     * @var Reader $reader
     */
    private $reader;

    /**
     * @var SteamWriter $writer
     */
    private $writer;

    public function __construct(KernelInterface $kernel, Reader $reader, SteamWriter $writer)
    {
        $this->rootPath = $kernel->getProjectDir();
        $this->reader = $reader;
        $this->writer = $writer;

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
        $csvStoragePath = $input->getArgument('path') ?? $this->rootPath . '/storage/data';
        $resultFilePath = $input->getArgument('result_file') ?? $this->rootPath . '/storage/result.csv';

        $cacheAdapter = new CacheAdapter(
            new FilesystemAdapter('groupCalculator', 0, $this->rootPath . '/storage/cache')
        );

        $this->clearCache($output, $cacheAdapter);

        $calc = new GroupCalculator($cacheAdapter);

        //Parser csv
        $this->parseCSV($output, $csvStoragePath, $calc);

        //Save result
        $this->saveResult($output, $resultFilePath, $calc);

        $this->clearCache($output, $cacheAdapter);

        $output->writeln([
            '',
            'Done',
        ]);
    }

    private function clearCache(OutputInterface $output, CacheAdapter $cacheAdapter)
    {
        if($this->clearCache){
            $output->writeln([
                '',
                'Clear cache',
                '============',
                '',
            ]);
            $cacheAdapter->clear();
        }
    }

    private function parseCSV(OutputInterface $output, string $csvStoragePath, GroupCalculator $calc)
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
            $calc->addition($data);
            $progressBar->advance();
        }

        $progressBar->finish();
    }

    private function saveResult(OutputInterface $output, string $resultFilePath, GroupCalculator $calc)
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
        foreach ($calc->getResult() as $row) {
            $this->writer->add($resultFilePath, $row->toArray());
            $progressBar->advance();
        }

        $progressBar->finish();
    }
}