<?php

namespace App\Tests\GroupCalculator\Reader;

use App\GroupCalculator\Model\DataObject;
use App\GroupCalculator\Reader\CSVReader;
use App\Tests\Traits\TemporarySpaceTrait;
use PHPUnit\Framework\TestCase;

class CSVReaderTest extends TestCase
{
    use TemporarySpaceTrait;

    /**
     * @var CSVReader $reader
     */
    private $reader;

    protected function setUp()
    {
        $this->reader = new CSVReader();
    }

    private function getFixture($name): string
    {
        //@todo overrive to fixtures
        return __DIR__ . '/../Fixtures/' . $name;
    }

    public function testNoData()
    {
        $path = $this->getFixture('nofiles');

        $this->assertCount(0, $this->reader->getData($path));
    }

    public function testGetData()
    {
        $path = $this->getFixture('onlycsv');

        $this->assertCount(5, $this->reader->getData($path));
    }

    public function testGetaDataObject()
    {
        $path = $this->getFixture('onlycsv');

        $this->assertInstanceOf(DataObject::class, $this->reader->getData($path)->current());
    }

    public function testIgnoreOtherFiles()
    {
        $path = $this->getFixture('noOnlyCsv');

        $this->assertCount(5, $this->reader->getData($path));
    }

    public function testSkipEmptyStrings()
    {
        $path = $this->getFixture('wrongData/emptyString');
        $this->assertCount(3, $this->reader->getData($path));
    }

    public function testSkipNoParamString()
    {
        $path = $this->getFixture('wrongData/noParam');
        $this->assertCount(1, $this->reader->getData($path));
    }

    public function testSkipWrongDate()
    {
        $path = $this->getFixture('wrongData/wrongDate');
        $this->assertCount(1, $this->reader->getData($path));
    }

    public function testSkipWrongParamType()
    {
        $path = $this->getFixture('wrongData/wrongParamType');
        $this->assertCount(1, $this->reader->getData($path));
    }
}
