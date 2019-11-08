<?php

namespace App\GroupCalculator\Writer;

class ArraySteamWriter implements SteamWriter
{
    private $data = [];

    public function add(array $data)
    {
        $this->data[] = $data;
    }

    public function getAll()
    {
        return $this->data;
    }

}