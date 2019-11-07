<?php

namespace App\GroupCalculator\Writer;

use App\GroupCalculator\Model\DataObject;

interface SteamWriter
{
    public function add(DataObject $data);
}