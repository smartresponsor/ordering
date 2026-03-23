#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\Order\Async\FileQueue;
use SmartResponsor\Order\Worker\WorkerPool;

$base = __DIR__ . '/../var/queue';
$tenant = $argv[2] ?? 'tenant_a';
$topic = $argv[3] ?? 'order_events';
$concurrency = (int)($argv[4] ?? 4);
$cycles = (int)($argv[5] ?? 100);

$q = new FileQueue($base);
$pool = new WorkerPool($q, $concurrency);
$pool->run($tenant, $topic, $cycles);
echo "OK\n";
