<?php

namespace App\Command;

use App\Component\Console\Command\Command;
use App\GroupCalculator\Model\DataObject;
use App\GroupCalculator\Writer\SteamWriter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class GeneratorCommand extends Command
{
    protected static $defaultName = 'app:generator';
    private $clearCache = true;

    /**
     * @var string
     */
    private $storageDir;

    /**
     * @var SteamWriter
     */
    private $writer;

    public function __construct(string $storageDir, SteamWriter $writer)
    {
        $this->storageDir = $storageDir;
        $this->writer = $writer;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = new \DateTimeImmutable('1980-01-01');
        $daysLimit = 10000;
        $filesLimit = 10;
        $storePath = $this->storageDir . "/data";

        $this->clearStorage($storePath);

        $progressBar = $this->initProgressBar($output, $filesLimit);
        $progressBar->start();

        for ($i = 0; $i < $filesLimit; $i++) {
            $generator = $this->generator($date, $daysLimit);
            $this->generateFile("$storePath/test{$i}.csv", $generator);

            $progressBar->advance();
        }

        $progressBar->finish();

        $output->writeln([
            '',
            'Done',
        ]);
    }

    private function clearStorage($storePath)
    {
        if ($this->clearCache && is_dir($storePath)) {
            $finder = new Finder();
            $finder
                ->files()
                ->in($storePath);

            if ($finder->hasResults()) {
                foreach ($finder as $file) {
                    unlink($file);
                }
            }
        }
    }

    private function generator(\DateTimeImmutable $startDate, int $limit): \Generator
    {
        for ($i = 0; $i < $limit; $i++) {
            $date = $startDate->modify("+$i days");
            $param1 = mt_rand(-100, 100);
            $param2 = mt_rand(-100, 100);
            $param3 = mt_rand(-100, 100);

            yield new DataObject($date, $param1, $param2, $param3);
        }
    }

    private function generateFile(string $path, \Generator $generator)
    {
        $this->writer->add($path, ['date', 'A', 'B', 'C']);

        foreach ($generator as $row) {
            $this->writer->add($path, $row->toArray());
        }
    }
}