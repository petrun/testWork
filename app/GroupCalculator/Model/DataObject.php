<?php

namespace App\GroupCalculator\Model;

class DataObject
{
    private $date;
    private $param1;
    private $param2;
    private $param3;

    public function __construct($date, $param1, $param2, $param3)
    {
        $this->date = $date;
        $this->param1 = trim($param1);
        $this->param2 = trim($param2);
        $this->param3 = trim($param3);
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getParam1()
    {
        return $this->param1;
    }

    /**
     * @return mixed
     */
    public function getParam2()
    {
        return $this->param2;
    }

    /**
     * @return mixed
     */
    public function getParam3()
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