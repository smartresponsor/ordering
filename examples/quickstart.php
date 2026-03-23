<?php
require __DIR__.'/../src/Http.php';
require __DIR__.'/../src/Client.php';
require __DIR__.'/../src/Webhook.php';

use SmartResponsor\OrderSDK\Client;
$base = getenv('API_BASE') ?: 'http://localhost:8080';
$c = new Client($base);
$o = $c->createOrder(1999,'USD','cus_php');
echo "order ".$o['id']."\n";
$c->transition($o['id'], 'confirm', 'php-qstart-1');
echo "confirmed\n";
