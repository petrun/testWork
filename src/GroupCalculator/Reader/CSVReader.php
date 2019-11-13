<?php

namespace App\GroupCalculator\Reader;

use App\GroupCalculator\Model\DataObject;
use Symfony\Component\Finder\Finder;

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
                try{
                    list($date, $param1, $param2, $param3) = $data;
                    $date = \DateTimeImmutable::createFromFormat('Y-m-d', $date);
                    yield new DataObject($date, $param1, $param2, $param3);
                }catch (\TypeError $e){
                    continue;
                }

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