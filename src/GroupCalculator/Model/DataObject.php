<?php

namespace App\GroupCalculator\Model;

use Webmozart\Assert\Assert;

class DataObject
{
    /**
     * @var \DateTimeImmutable $date
     */
    private $date;

    /**
     * @var float $param1
     */
    private $param1;

    /**
     * @var float $param2
     */
    private $param2;

    /**
     * @var float $param3
     */
    private $param3;

    public function __construct(\DateTimeImmutable $date, float $param1, float $param2, float $param3)
    {
        $this->date = $date;
        $this->param1 = $param1;
        $this->param2 = $param2;
        $this->param3 = $param3;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): string
    {
        return $this->date->format('Y-m-d');
    }

    /**
     * @return float
     */
    public function getParam1(): float
    {
        return $this->param1;
    }

    /**
     * @return float
     */
    public function getParam2(): float
    {
        return $this->param2;
    }

    /**
     * @return float
     */
    public function getParam3(): float
    {
        return $this->param3;
    }

    public function addition(DataObject $data): self
    {
        $this->param1 += $data->getParam1();
        $this->param2 += $data->getParam2();
        $this->param3 += $data->getParam3();

        return $this;
    }

    public function toArray()
    {
        return [
            'date' => $this->getDate(),
            'param1' => $this->getParam1(),
            'param2' => $this->getParam2(),
            'param3' => $this->getParam3(),
        ];
    }

}