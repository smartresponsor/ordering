#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Service\Transport\Order\Client;
use App\Support\Security\Order\Idempotency;

$base = getenv('BASE') ?: 'http://127.0.0.1:8000';
$token = getenv('TOKEN') ?: null;
$client = new Client($base, $token);

$cmd = $argv[1] ?? 'help';

try {
    if ('create-confirm' === $cmd) {
        $order = $client->createOrder(1999, 'USD', 'cus_001');
        $orderId = (string)($order['id'] ?? '');
        if ('' === $orderId) {
            throw new RuntimeException('Order id missing in create response');
        }

        $client->transition($orderId, 'confirm', Idempotency::key());
        fwrite(STDOUT, json_encode(['orderId' => $orderId, 'status' => 'confirmed'], JSON_THROW_ON_ERROR) . "\n");
        exit(0);
    }

    fwrite(STDOUT, "Usage: sr-order.php create-confirm\n");
    exit(0);
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage() . "\n");
    exit(1);
}
