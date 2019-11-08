<?php

namespace App\GroupCalculator\Reader;

interface Reader
{
    public function getData(): \Generator;
}