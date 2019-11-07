<?php

namespace App\GroupCalculator\Reader;

use App\GroupCalculator\Model\DataObject;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class CSVReader implements Reader
{
    private $finder;

    public function __construct($path)
    {
        $this->finder = new Finder();
        $this->finder
            ->files()
            ->in($path)
            ->name('*.csv');
    }

    public function getData(): \Generator
    {
        foreach ($this->getIterator() as $file) {
            $absoluteFilePath = $file->getRealPath();

            $iterator = $this->loadFile($absoluteFilePath);

            foreach ($iterator as $data) {
                if (count($data) != 4) {
                    continue;
                }
                list($date, $param1, $param2, $param3) = $data;
                yield new DataObject($date, $param1, $param2, $param3);
            }
        }
    }

    private function loadFile($path): \Iterator
    {
        $file = new \SplFileObject($path, "r");
        $file->setFlags(\SplFileObject::READ_CSV);
        $file->setCsvControl(';');
        $iterator = new \LimitIterator($file, 1);
        return $iterator;
    }

    /**
     * Returns an Iterator for the current Finder configuration.
     *
     * This method implements the IteratorAggregate interface.
     *
     * @return \Iterator|SplFileInfo[] An iterator
     *
     * @throws \LogicException if the in() method has not been called
     */
    public function getIterator()
    {
        return $this->finder->getIterator();
    }

    function count(): int
    {
        return $this->finder->count();
    }
}