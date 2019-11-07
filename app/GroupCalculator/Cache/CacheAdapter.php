<?php


namespace App\GroupCalculator\Cache;


use App\GroupCalculator\Model\DataObject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

class CacheAdapter
{
    /**
     * @var AbstractAdapter $cache
     */
    private $cache;

    public function __construct(AbstractAdapter $cache)
    {
        $this->cache = $cache;
    }

    public function set(string $key, DataObject $data)
    {
        $cachedItem = $this->cache->getItem($key);
        $cachedItem->set($data);
        $this->cache->save($cachedItem);
    }

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
        return $this->cache->getAll();
    }
}