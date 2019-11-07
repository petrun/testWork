<?php

namespace App\GroupCalculator\Writer;

use App\GroupCalculator\Model\DataObject;

class CSVSteamWriter implements SteamWriter
{
    private $fileHandler;

    public function __construct($path)
    {
        $this->fileHandler = new \SplFileObject($path, 'w');
        $this->fileHandler->setCsvControl(';');
//        $this->fileHandler->flock(LOCK_EX);
    }

    public function setHeader(array $data){
        $this->fileHandler->fputcsv($data);
    }

    public function add(DataObject $data){
        $this->fileHandler->fputcsv($data->toArray());
    }

}