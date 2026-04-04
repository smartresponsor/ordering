<?php

declare(strict_types=1);

use App\Service\Transport\Order\Client;
use App\Support\Security\Order\Idempotency;

/**
 * Create an order and confirm it through the current transport client.
 */
require __DIR__ . '/../vendor/autoload.php';

$client = new Client('https://api.smartresponsor.local');
$order = $client->createOrder(1999, 'USD', 'cus_001');
$client->transition((string) $order['id'], 'confirm', Idempotency::key());
echo "OK\n";
