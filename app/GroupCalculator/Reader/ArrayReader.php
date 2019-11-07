<?php

namespace App\GroupCalculator\Reader;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ArrayReader implements Reader
{
    /**
     * @var SplFileInfo[]
     */
    private $iterator;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Returns an Iterator for the current Finder configuration.
     *
     * This method implements the IteratorAggregate interface.
     *
     * @return \Iterator|SplFileInfo[] An iterator
     *
     * @throws \LogicException if the in() method has not been called
     */
    public function getIterator()
    {
//        return $this->finder->getIterator();
    }

    function count(): int
    {
        return count($this->data);
    }
}