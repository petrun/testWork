<?php

namespace App\GroupCalculator;

use App\GroupCalculator\Model\Data;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

class GroupCalculator
{
    /**
     * @var AbstractAdapter $cache
     */
    private $cache;

    private $keys = [];

    public function __construct(AbstractAdapter $cache)
    {
        $this->cache = $cache;
    }


    function addition(Data $data)
    {
        $key = $data->getDate();

        if ($prev = $this->getCachedItem($key)) {
            $result = $prev->addition($data);
        } else {
            $result = $data;
        }

        $this->setCache($key, $result);
    }

    private function setCache(string $key, Data $data) //: ?Data
    {
        $cachedItem = $this->cache->getItem($key);
        $cachedItem->set($data);
        $this->cache->save($cachedItem);
        $this->keys[$key] = 1;
    }

    /**
     * @param string $key
     * @return Data|null
     */
    private function getCachedItem(string $key): ?Data
    {
        if ($this->cache->hasItem($key))
        {
            $cachedItem = $this->cache->getItem($key);
            return $cachedItem->get();
        }
        return null;
    }

    function getResult(): \Generator
    {
//        dd(array_keys($this->keys));
        $cachedItems = $this->cache->getItems(array_keys($this->keys));

        foreach ($cachedItems as $item){
            yield $item->get();
        }


//        dd($this->cache->getItems(array_keys($this->keys)));
//        dd('die getResult');
//        return $this->cache->getAll();
    }

}