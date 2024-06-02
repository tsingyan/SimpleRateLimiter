<?php

namespace SimpleRateLimiter\Strategy;

use SimpleRateLimiter\Storage\StorageInterface;

class TokenBucketStrategy implements StrategyInterface
{
    public function isAllowed($key, StorageInterface $storage, $limit, $window): bool
    {
        $currentTime = microtime(true);
        $data = $storage->get($key);
        if ($data === null) {
            $data = [
                'tokens' => $limit,
                'last_time' => $currentTime
            ];
        }
        $lastTimeInterval = max(0, $currentTime - $data["last_time"]);
        $tobeFilledTokens = min($limit, $data["tokens"] + $lastTimeInterval * $window);
        $data["tokens"] = $tobeFilledTokens;
        $data["last_time"] = $currentTime;
        if ($data["tokens"] >= 1) {
            $data["tokens"]--;
            $storage->set($key, $data);
            return true;
        } else {
            $storage->set($key, $data);
            return false;
        }
    }
}