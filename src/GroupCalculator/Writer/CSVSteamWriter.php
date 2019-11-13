<?php

namespace App\GroupCalculator\Writer;

use App\GroupCalculator\Model\DataObject;
use Symfony\Component\Filesystem\Exception\InvalidArgumentException;

class CSVSteamWriter implements SteamWriter
{
    /**
     * @var array|\SplFileObject[]
     */
    private $fileHandlers = [];

    private function create(string $path)
    {
        $directory = \dirname($path);
        if (!is_dir($directory) && !@mkdir($directory, 0777, true)) {
            throw new InvalidArgumentException(sprintf('Directory does not exist and cannot be created: %s.', $directory));
        }

        if (!is_writable($directory)) {
            throw new InvalidArgumentException(sprintf('Directory is not writable: %s.', $directory));
        }

        $fileHandler = new \SplFileObject($path, 'w');
        $fileHandler->setCsvControl(';');
//        $this->fileHandler->flock(LOCK_EX);
        return $fileHandler;
    }

    public function add(string $namespace, array $data)
    {
        if(empty($this->fileHandlers[$namespace])){
            $this->fileHandlers[$namespace] = $this->create($namespace);
        }

        $this->fileHandlers[$namespace]->fputcsv($data);
    }

}