<?php

namespace App\GroupCalculator\Writer;

interface SteamWriter
{
    public function add(string $namespace, array $data);
}