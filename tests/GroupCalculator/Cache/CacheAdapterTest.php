<?php

namespace App\Tests\GroupCalculator\Cache;

use App\Cache\Adapter\FilesystemAdapter;
use App\GroupCalculator\Cache\CacheAdapter;
use App\GroupCalculator\Model\DataObject;
use App\Tests\Cache\Adapter\FilesystemAdapterTest;
use App\Tests\Traits\TemporarySpaceTrait;
use PHPUnit\Framework\TestCase;

class CacheAdapterTest extends TestCase
{
     use TemporarySpaceTrait;

    /**
     * @var CacheAdapter $cache
     */
    private $cache;

    /**
     * @var string $cacheDir
     */
    private $cacheDir;

    protected function setUp()
    {
        $this->cacheDir = $this->tempSpaceUp('CacheAdapterTest');

        $this->cache = new CacheAdapter(
            new FilesystemAdapter(
                'groupCalculator',
                0,
                $this->cacheDir
            )
        );
    }

    protected function tearDown()
    {
        $this->tempSpaceDown($this->cacheDir);
    }

    private function createDataObject(string $date = '2018-01-01', $param1 = 10, $param2 = 15, $param3 = 20): DataObject
    {
        return new DataObject(new \DateTimeImmutable($date), $param1, $param2, $param3);
    }

    public function testSet()
    {
        $data = $this->createDataObject();
        $this->cache->set('test', $data);

        $cached = $this->cache->get('test');

        $this->assertEquals($cached, $data);
    }

    public function testOverrideCache()
    {
        $data1 = $this->createDataObject('2018-01-01', 10, 15, 20);
        $data2 = $this->createDataObject('2018-02-02', 1, 12, 2);
        $this->cache->set('test', $data1);
        $this->cache->set('test', $data2);

        $cached = $this->cache->get('test');

        $this->assertEquals($cached, $data2);
    }

    public function testGetAll()
    {
        $data = $this->createDataObject();
        $this->cache->set('test', $data);
        $this->cache->set('test1', $data);

        $cached = $this->cache->getAll();

        $this->assertCount(2, $cached);
    }

    public function testClear()
    {
        $data = $this->createDataObject();
        $this->cache->set('test', $data);
        $this->cache->clear();

        $cached = $this->cache->getAll();

        $this->assertCount(0, $cached);
    }
}
