#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\Order\Async\FileQueue;

$base = __DIR__ . '/../var/queue';
$tenant = $argv[2] ?? 'tenant_a';
$topic = $argv[3] ?? 'order_events';
$n = (int)($argv[4] ?? 1000);
$delay = (int)($argv[5] ?? 0);

$q = new FileQueue($base);
$start = microtime(true);
for ($i=0; $i<$n; $i++) {
    $q->enqueue($tenant, $topic, ['i'=>$i], $delay);
}
$dt = microtime(true) - $start;
fwrite(STDOUT, json_encode(['enqueued'=>$n,'seconds'=>$dt,'eps'=>$n/max(0.001,$dt)])."\n");
