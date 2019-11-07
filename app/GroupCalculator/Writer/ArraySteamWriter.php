<?php

namespace App\GroupCalculator\Writer;

use App\GroupCalculator\Model\Data;

class ArraySteamWriter implements SteamWriter
{
    private $data = [];

    public function __construct()
    {
        //create file
    }

    public function add(Data $data){
        $this->data[] = $data;
    }

    public function getAll(){
        return $this->data;
    }

}