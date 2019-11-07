<?php

namespace App\GroupCalculator\Writer;

use App\GroupCalculator\Model\Data;

interface SteamWriter
{
    public function add(Data $data);
}