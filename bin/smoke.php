#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Entity\Order;

$flow = $argv[1] ?? 'basic';
$order = new Order('USD', '19.99');

match ($flow) {
    'basic' => $order->markPaid('19.99'),
    'cancel' => $order->setStatus('cancelled'),
    'return' => $order->markRefunded('19.99'),
    default => $order->setStatus('draft'),
};

echo json_encode(['orderId' => $order->id(), 'final' => $order->status()], JSON_THROW_ON_ERROR) . PHP_EOL;
