<?php

namespace SimpleRateLimiter\Strategy;

use SimpleRateLimiter\Storage\RedisStorage;
use SimpleRateLimiter\Storage\StorageInterface;

class RedisLuaTokenBucketStrategy extends TokenBucketStrategy
{
    private string $_script;

    public function __construct()
    {
        $this->_script = <<<LUA
            local key = KEYS[1]  
            local limit = tonumber(ARGV[1])  
            local rate = tonumber(ARGV[2])  
            local currentTime = tonumber(ARGV[3])  
              
            local tokens  
            local lastTime  
            local data = redis.call('get', key)  
              
            if data then   
                local decoded = cjson.decode(data)  
                tokens = tonumber(decoded['tokens'])  
                lastTime = tonumber(decoded['last_time'])  
            else   
                tokens = limit  
                lastTime = currentTime  
            end  
              
            local intervalSeconds = math.max(0, currentTime - lastTime)  
            local newTokens = math.min(limit, tokens + rate * intervalSeconds)  
              
            if newTokens < 1 then   
                return -1   
            else   
                newTokens = newTokens-1
                local newData = cjson.encode({tokens = math.floor(newTokens), last_time = currentTime})  
                redis.call('set', key, newData)  
                return newTokens 
            end    
        LUA;
    }

    /**
     * @param string $key key ...
     * @param StorageInterface $storage
     * @param int $limit Bucket cap
     * @param int $window Bucket rate
     * @return bool
     */
    public function isAllowed($key, StorageInterface $storage, $limit, $window): bool
    {
        if (!($storage instanceof RedisStorage)) {
            throw new \InvalidArgumentException("RedisStorage is required for RedisLuaStrategy");
        }

        $currentTime = microtime(true);
        $result = $storage->eval($this->_script, [$key], [$limit, $window, $currentTime]);
        return ($result >= 0);
    }
}