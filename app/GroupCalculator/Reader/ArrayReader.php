<?php

namespace App\GroupCalculator\Reader;

use App\GroupCalculator\Model\DataObject;

class ArrayReader implements Reader
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getData(): \Generator
    {
        foreach ($this->data as $row)
        {
            if($row instanceof DataObject){
                yield $row;
            } else {
                if (count($row) != 4) {
                    continue;
                }
                list($date, $param1, $param2, $param3) = $row;
                yield new DataObject($date, $param1, $param2, $param3);
            }
        }
    }
}