<?php

namespace App\GroupCalculator\Reader;

interface Reader
{
    public function getData(string $path): \Generator;
}