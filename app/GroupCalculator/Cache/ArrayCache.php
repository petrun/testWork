<?php

namespace App\GroupCalculator\Cache;

class ArrayCache implements Cache
{
    private $data = [];

    public function get($key)
    {
        if(isset($this->data[$key])){
            return $this->data[$key];
        }
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function clear()
    {
        $this->data = [];
    }

}