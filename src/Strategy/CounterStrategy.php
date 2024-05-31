<?php

namespace SimpleRateLimiter\Strategy;

use SimpleRateLimiter\Storage\StorageInterface;

class CounterStrategy implements StrategyInterface
{
    public function isAllowed($key, StorageInterface $storage, $limit, $window) : bool
    {
        $storeData = $storage->get($key);
        $currentTime = time();
        $limitData = [
            "count" => 0,
            "start_time" => $currentTime,
        ];
        if ($storeData) {
            $limitData = $storeData;
        }
        if (($currentTime - $limitData["start_time"]) >= $window) {
            $limitData["count"] = 0;
        }

        $limitData["count"]++;
        $limitData["start_time"] = $currentTime;
        print_r([
                "limitData" => $limitData,
            ]
        );
        if ($limitData["count"] <= $limit) {
            $storage->set($key, $limitData, $window);
            return true;
        }
        return false;
    }
}