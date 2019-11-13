<?php

namespace App\Tests\GroupCalculator\Writer;

use App\GroupCalculator\Writer\CSVSteamWriter;
use App\Tests\Traits\TemporarySpaceTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class CSVSteamWriterTest extends TestCase
{
    use TemporarySpaceTrait;

    /**
     * @var CSVSteamWriter $writer
     */
    private $writer;

    /**
     * @var string $cacheDir
     */
    private $cacheDir;

    /**
     * @var CsvEncoder
     */
    private $encoder;

    protected function setUp()
    {
        $this->writer = new CSVSteamWriter();
        $this->cacheDir = $this->tempSpaceUp('CSVSteamWriterTest');
        $this->encoder = new CsvEncoder();
    }

    protected function tearDown()
    {
        $this->writer = null;
        $this->tempSpaceDown($this->cacheDir);
    }

    public function testCreate()
    {
        $fileName = $this->cacheDir . '/test.csv';
        $this->writer->create($fileName);
        $this->assertTrue(file_exists($fileName));
    }

    public function testAdd()
    {
        $data = [
            'test1',
            'test2'
        ];
        $fileName = $this->cacheDir . '/test.csv';

        $this->writer->create($fileName);
        $this->writer->add($data);

        $result = file_get_contents($fileName);

        $csv = $this->encoder->encode([$data], 'csv', [
            CsvEncoder::DELIMITER_KEY => ';',
            CsvEncoder::NO_HEADERS_KEY => true
        ]);

        $this->assertEquals($result, $csv);
    }
}
