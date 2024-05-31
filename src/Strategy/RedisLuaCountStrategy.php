<?php

namespace Strategy;

use Storage\StorageInterface;
use Storage\RedisStorage;

class RedisLuaCountStrategy extends CountStrategy
{
    private string $_script;

    public function __construct()
    {
        $this->_script = <<<LUA
            local key = KEYS[1]
            local limit = tonumber(ARGV[1])
            local interval = tonumber(ARGV[2])
            local current = redis.call("INCR", key)
            if current == 1 then 
                redis.call("EXPIRE", key, interval)
            end 
            if current > limit then 
                return 0
            else return 1
            end 
        LUA;
    }

    public function isAllowed($key, StorageInterface $storage, $limit, $window) : bool
    {
        if (!($storage instanceof RedisStorage)) {
            throw new \InvalidArgumentException("RedisStorage is required for RedisLuaStrategy");
        }

        $currentTime = microtime(true);
        $result = $storage->eval($this->_script, [$key], [$limit, $window, $currentTime]);
        return ($result >= 0);
    }
}