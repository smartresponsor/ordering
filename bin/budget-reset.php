#!/usr/bin/env php
<?php

declare(strict_types=1);

$host = getenv('REDIS_HOST') ?: '127.0.0.1';
$port = (int) (getenv('REDIS_PORT') ?: 6379);
$pattern = getenv('BUDGET_KEY_PATTERN') ?: 'budget:*';

if (!class_exists(Redis::class)) {
    fwrite(STDERR, "Redis extension is not installed.\n");
    exit(1);
}

$redis = new Redis();
if (false === $redis->connect($host, $port)) {
    fwrite(STDERR, sprintf("Unable to connect to Redis at %s:%d\n", $host, $port));
    exit(1);
}

$keys = $redis->keys($pattern);
if (false === $keys) {
    fwrite(STDERR, sprintf("Unable to query keys for pattern '%s'\n", $pattern));
    exit(1);
}

$deleted = 0;
foreach ($keys as $key) {
    $deleted += (int) $redis->del((string) $key);
}

fwrite(STDOUT, sprintf("budget keys cleared: %d\n", $deleted));
