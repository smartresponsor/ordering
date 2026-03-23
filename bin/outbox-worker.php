#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\Order\Outbox\FileOutboxRepository;
use SmartResponsor\Order\Telemetry\FileTelemetry;
use SmartResponsor\Order\Worker\OutboxWorker;
use SmartResponsor\Order\Webhook\NoopWebhookDispatcher;
$dir = __DIR__ . '/../var';
$repo = new FileOutboxRepository($dir.'/outbox');
$telemetry = new FileTelemetry($dir.'/metric/metric.json');
$worker = new OutboxWorker($repo, new NoopWebhookDispatcher(), $telemetry);
$cycle = (int)($argv[2] ?? 1);
for($i=0;$i<$cycle;$i++){ $worker->runCycle(); usleep(200000); }
echo "OK\n";
