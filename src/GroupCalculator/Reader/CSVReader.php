<?php

namespace App\GroupCalculator\Reader;

use App\GroupCalculator\Model\DataObject;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class CSVReader implements Reader
{
    private $finder;

    public function getData(string $path): \Generator
    {
        $this->finder = new Finder();
        $this->finder
            ->files()
            ->in($path)
            ->name('*.csv');

        foreach ($this->finder->getIterator() as $file) {
            $absoluteFilePath = $file->getRealPath();

            $iterator = $this->loadFile($absoluteFilePath);

            foreach ($iterator as $data) {
//                if (count($data) != 4) {
//                    continue;
//                }
                list($date, $param1, $param2, $param3) = $data;
                $date = \DateTimeImmutable::createFromFormat('Y-m-d', $date);
                yield new DataObject($date, $param1, $param2, $param3);
            }
        }
    }

    private function loadFile($path): \Iterator
    {
        $file = new \SplFileObject($path, "r");
        $file->setFlags(\SplFileObject::READ_CSV
                        | \SplFileObject::READ_AHEAD
                        | \SplFileObject::SKIP_EMPTY
                        | \SplFileObject::DROP_NEW_LINE);
        $file->setCsvControl(';');
        $iterator = new \LimitIterator($file, 1);
        return $iterator;
    }
}