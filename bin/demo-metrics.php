#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\Order\Observability\Metric;
use SmartResponsor\Order\Observability\PaymentMetrics;
$m = new Metric(__DIR__.'/../var/metric');
$pm = new PaymentMetrics($m);
$pm->providerLatencyMs(180+mt_rand(0,70), 'stripe', 'authorize');
$pm->providerError('stripe','capture', (string)(200 + (mt_rand(0,10)==0?500:200)));
$pm->webhookVerifyFail('stripe');
echo "ok\n";
