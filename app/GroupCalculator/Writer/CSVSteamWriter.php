<?php

namespace App\GroupCalculator\Writer;

use App\GroupCalculator\Model\DataObject;
use Symfony\Component\Filesystem\Exception\InvalidArgumentException;

class CSVSteamWriter implements SteamWriter
{
    private $fileHandler;

    public function __construct($path)
    {
        $directory = \dirname($path);
        if (!is_dir($directory) && !@mkdir($directory, 0777, true)) {
            throw new InvalidArgumentException(sprintf('Directory does not exist and cannot be created: %s.', $directory));
        }

        if (!is_writable($directory)) {
            throw new InvalidArgumentException(sprintf('Directory is not writable: %s.', $directory));
        }

        $this->fileHandler = new \SplFileObject($path, 'w');
        $this->fileHandler->setCsvControl(';');
//        $this->fileHandler->flock(LOCK_EX);
    }

    public function add(array $data)
    {
        $this->fileHandler->fputcsv($data);
    }

}