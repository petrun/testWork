<?php

namespace App\GroupCalculator;

use App\GroupCalculator\Cache\CacheAdapter;
use App\GroupCalculator\Model\DataObject;

class GroupCalculator
{
    /**
     * @var CacheAdapter $cache
     */
    private $cache;

    public function __construct(CacheAdapter $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param DataObject $data
     * @throws \Psr\Cache\InvalidArgumentException
     */
    function addition(DataObject $data)
    {
        $key = $data->getDate();

        if ($prev = $this->cache->get($key)) {
            $result = $prev->addition($data);
        } else {
            $result = $data;
        }

        $this->cache->set($key, $result);
    }

    function getResult(): \Generator
    {
        return $this->cache->getAll();
    }

}