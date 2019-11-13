<?php

namespace App\GroupCalculator;

use App\GroupCalculator\Cache\CacheAdapter;
use App\GroupCalculator\Model\DataObject;

interface GroupCalculatorInterface
{
    public function __construct(CacheAdapter $cache);

    public function clearCache();

    /**
     * @param DataObject $data
     */
    function addition(DataObject $data);

    function getResult(): \Generator;

}