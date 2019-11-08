<?php


namespace App\GroupCalculator\Cache;


use App\GroupCalculator\Model\DataObject;
use App\Cache\Adapter\AdapterInterface;

class CacheAdapter
{
    /**
     * @var AdapterInterface $cache
     */
    private $cache;

    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $key
     * @param DataObject $data
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function set(string $key, DataObject $data)
    {
        $cachedItem = $this->cache->getItem($key);
        $cachedItem->set($data);
        $this->cache->save($cachedItem);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get(string $key, $default = null)
    {
        if ($this->cache->hasItem($key)) {
            $cachedItem = $this->cache->getItem($key);
            return $cachedItem->get();
        }
        return $default;
    }

    public function clear()
    {
        $this->cache->clear();
    }

    public function getAll()
    {
        return $this->cache->getAllItems();
    }
}