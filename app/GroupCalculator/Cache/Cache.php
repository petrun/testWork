<?php

namespace App\GroupCalculator\Cache;

interface Cache
{
    public function get($key);
    public function set($key, $value);
    public function getAll(): array;
    public function clear();
}