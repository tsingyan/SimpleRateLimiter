<?php

namespace SimpleRateLimiter\Strategy;

use SimpleRateLimiter\Storage\StorageInterface;

interface StrategyInterface
{
    public function isAllowed($key, StorageInterface $storage, $limit, $window): bool;
}