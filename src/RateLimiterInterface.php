<?php

namespace SimpleRateLimiter;

interface RateLimiterInterface
{
    public function isAllow($key, $limit, $window): bool;
}