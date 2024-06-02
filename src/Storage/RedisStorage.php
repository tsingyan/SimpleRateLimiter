<?php

namespace SimpleRateLimiter\Storage;

use Redis;

class RedisStorage implements StorageInterface
{
    protected Redis $_client;

    public function __construct(Redis $client)
    {
        $this->_client = $client;
    }

    public function get($key)
    {
        $data = $this->_client->get($key);
        return $data ? json_decode($data, true) : null;
    }

    public function set($key, $value, $ttl = null)
    {
        $data = json_encode($value);
        if ($ttl) {
            $this->_client->set($key, $data, $ttl);
        } else {
            $this->_client->set($key, $data);
        }
    }

    public function delete($key)
    {
        $this->_client->del($key);
    }

    public function eval($script, $keys = [], $args = [])
    {
        return $this->_client->eval($script, array_merge($keys, $args), count($keys));
    }
}