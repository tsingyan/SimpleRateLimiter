# RateLimiter

A rate limiter library for PHP, supporting multiple strategies and storage backends.

## Installation

Install via Composer:

```sh
composer require yourname/ratelimiter
```

## 项目结构

## Usage

FileStorage & CounterStrategy

```
use SimpleRateLimiter\RateLimiter;
use SimpleRateLimiter\Storage\FileStorage;
use SimpleRateLimiter\Strategy\CounterStrategy;


$fileStorage = new FileStorage('./file-count-rate-limiter/');
$counterStrategy = new CounterStrategy();
$rateLimiter = new RateLimiter($fileStorage, $counterStrategy);

$key = 'user123';
$limit = 10; // limitCount
$window = 10; // expire
if ($rateLimiter->isAllow($key, $limit, $window)) {
    echo "Request allowed\n";
} else {
    echo "Request blocked\n";
}
```

RedisStorage & RedisLuaCounterStrategy

```
use SimpleRateLimiter\RateLimiter;
use SimpleRateLimiter\Storage\RedisStorage;
use SimpleRateLimiter\Strategy\RedisLuaCounterStrategy;
$redis = new redis();
$redis->connect("172.17.0.5", 6379);
$redisStorage = new RedisStorage($redis);
$counterStrategy = new RedisLuaCounterStrategy();
$rateLimiter = new RateLimiter($redisStorage, $counterStrategy);

$key = "rate_limit_count-user123";
$limit = 10; // limitCount
$window = 30; // expire

$ret = $rateLimiter->isAllow($key, $limit, $window);
if ($ret) {
    echo "Request allowed\n";
} else {
    echo "Request blocked\n";
}
```

FileStorage & CounterStrategy

```
use SimpleRateLimiter\RateLimiter;
use SimpleRateLimiter\Storage\FileStorage;
use SimpleRateLimiter\Strategy\CounterStrategy;
$fileStorage = new FileStorage('path/to/');
$counterStrategy = new CounterStrategy();
$rateLimiter = new RateLimiter($fileStorage, $counterStrategy);

$key = 'user123';
$limit = 20; // cap
$window = 5; //rate

$ret = $rateLimiter->isAllow($key, $limit, $window);
if ($ret) {
    echo "Request allowed\n";
} else {
    echo "Request blocked\n";
}
```

RedisStorage & RedisLuaTokenBucketStrategy

```

```
