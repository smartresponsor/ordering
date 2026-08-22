#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Ordering\Service\Security\Order\HttpIdempotencyGuard;
use App\Ordering\Service\Security\Order\IdempotencyKeyPolicy;
use App\Ordering\Service\Security\Order\MemoryIdempotencyStore;

require __DIR__ . '/../vendor/autoload.php';

$policy = new IdempotencyKeyPolicy(60);
$store = new MemoryIdempotencyStore();
$http = new HttpIdempotencyGuard($policy, $store);
$body = json_encode(['order' => '001', 'amount' => 10.00], JSON_THROW_ON_ERROR);
$hdr = ['X-Idempotency-Token' => 'demo-1'];

$first = $http->allow('POST', '/order/pay', $body, $hdr);
$second = $http->allow('POST', '/order/pay', $body, $hdr);

echo $first ? "ACCEPTED\n" : "DUPLICATE\n";
echo $second ? "ACCEPTED\n" : "DUPLICATE\n";
