<?php

namespace App\GroupCalculator\Writer;

use App\GroupCalculator\Model\DataObject;

class ArraySteamWriter implements SteamWriter
{
    private $data = [];

    public function __construct()
    {
        //create file
    }

    public function add(DataObject $data){
        $this->data[] = $data;
    }

    public function getAll(){
        return $this->data;
    }

}