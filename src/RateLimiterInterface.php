<?php

interface RateLimiterInterface
{
    public function isAllow($key, $limit, $window) :bool;
}