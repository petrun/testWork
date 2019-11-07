<?php

namespace App\GroupCalculator;

use App\GroupCalculator\Cache\CacheAdapter;
use App\GroupCalculator\Model\DataObject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

class GroupCalculator
{
    /**
     * @var CacheAdapter $cache
     */
    private $cache;

//    private $keys = [];

    public function __construct(CacheAdapter $cache)
    {
        $this->cache = $cache;
    }

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

//    private function setCache(string $key, DataObject $data) //: ?Data
//    {
//        $cachedItem = $this->cache->getItem($key);
//        $cachedItem->set($data);
//        $this->cache->save($cachedItem);
////        $this->keys[$key] = 1;
//    }

    /**
     * @param string $key
     * @return DataObject|null
     */
//    private function getCachedItem(string $key): ?DataObject
//    {
//        if ($this->cache->hasItem($key))
//        {
//            $cachedItem = $this->cache->getItem($key);
//            return $cachedItem->get();
//        }
//        return null;
//    }

    function getResult(): \Generator
    {
        dd('res1');
//        dd(array_keys($this->keys));
//        $cachedItems = $this->cache->getItems(array_keys($this->keys));

        foreach ($cachedItems as $item){
            dd($item->get());
            yield $item->get();
        }


//        dd($this->cache->getItems(array_keys($this->keys)));
//        dd('die getResult');
//        return $this->cache->getAll();
    }

}