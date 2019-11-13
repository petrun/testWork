<?php

namespace App\Tests\GroupCalculator;

use App\Cache\Adapter\FilesystemAdapter;
use App\GroupCalculator\Cache\CacheAdapter;
use App\GroupCalculator\GroupCalculator;
use App\GroupCalculator\Model\DataObject;
use App\Tests\Traits\TemporarySpaceTrait;
use PHPUnit\Framework\TestCase;

class GroupCalculatorTest extends TestCase
{
    use TemporarySpaceTrait;

    /**
     * @var GroupCalculator $calc
     */
    private $calc;

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
        $this->cacheDir = $this->tempSpaceUp('GroupCalculatorTest');

        $this->cache = new CacheAdapter(
            new FilesystemAdapter('groupCalculator', 0, $this->cacheDir)
        );

        $this->calc = new GroupCalculator($this->cache);
    }

    protected function tearDown()
    {
        $this->tempSpaceDown($this->cacheDir);
    }

    public function testCombineRows()
    {
        $data1 = new DataObject(new \DateTimeImmutable('2018-01-01'), 10, 15, 20);
        $data2 = new DataObject(new \DateTimeImmutable('2018-01-01'), 1, 12, 2);

        $this->calc->addition($data1);
        $this->calc->addition($data2);

        $result = $this->calc->getResult();

        $this->assertCount(1, $result);
    }

    public function testAddition()
    {
        $data1 = new DataObject(new \DateTimeImmutable('2018-01-01'), 10, 15, 20);
        $data2 = new DataObject(new \DateTimeImmutable('2018-01-01'), 1, 12, 2);
        $data3 = new DataObject(new \DateTimeImmutable('2018-01-01'), 11, 27, 22);

        $this->calc->addition($data1);
        $this->calc->addition($data2);

        $result = $this->calc->getResult();

        $resultData = $result->current();

        $this->assertEquals($resultData , $data3);
    }
}
