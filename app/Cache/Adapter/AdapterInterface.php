<?php


namespace App\Cache\Adapter;

use Symfony\Component\Cache\Adapter\AdapterInterface as BaseAdapterInterface;


interface AdapterInterface extends BaseAdapterInterface
{
    /**
     * Returns all cache items.
     *
     * @return \Generator
     */
    public function getAllItems();
}