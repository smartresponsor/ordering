<?php

declare(strict_types=1);

use App\Service\Transport\Order\Client;

/**
 * Minimal PHP quickstart against the current order transport client.
 */
require __DIR__ . '/../vendor/autoload.php';

$base = getenv('API_BASE') ?: 'http://localhost:8080';
$client = new Client($base);
$order = $client->createOrder(1999, 'USD', 'cus_php');

printf("order %s\n", $order['id']);
$client->transition((string) $order['id'], 'confirm', 'php-qstart-1');
echo "confirmed\n";
