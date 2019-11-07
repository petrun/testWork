<?php

namespace App\GroupCalculator\Writer;

use App\GroupCalculator\Model\Data;

class CSVSteamWriter implements SteamWriter
{
    private $fileHandler;

    public function __construct($path)
    {
        $this->fileHandler = new \SplFileObject($path, 'w');
        $this->fileHandler->setCsvControl(';');
//        $this->fileHandler->flock(LOCK_EX);
    }

    public function add(Data $data){
        $this->fileHandler->fputcsv($data->toArray());
    }

}