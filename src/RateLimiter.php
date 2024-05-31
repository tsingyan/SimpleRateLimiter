<?php

namespace SimpleRateLimiter;

use SimpleRateLimiter\Storage\StorageInterface;
use SimpleRateLimiter\Strategy\StrategyInterface;

class RateLimiter implements RateLimiterInterface
{
    public StorageInterface $storage;

    public StrategyInterface $strategy;

    public function __construct(StorageInterface $storage, StrategyInterface $strategy )
    {
        $this->storage = $storage;
        $this->strategy = $strategy;
    }

    public function isAllow($key, $limit, $window): bool
    {
        return $this->strategy->isAllowed($key, $this->storage, $limit, $window);
    }
}