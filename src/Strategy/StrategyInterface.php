<?php

namespace Strategy;

use Storage\StorageInterface;
interface StrategyInterface
{
    public function isAllowed($key, StorageInterface $storage, $limit, $window) :bool;
}