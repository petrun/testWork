<?php

namespace App\GroupCalculator\Writer;

interface SteamWriter
{
    public function create(string $path);
    public function add(array $data);
}