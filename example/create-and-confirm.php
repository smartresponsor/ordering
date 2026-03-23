<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\OrderSDK\Http\HttpClient;
use SmartResponsor\OrderSDK\Client\OrderClient;
use SmartResponsor\OrderSDK\Util\Idempotency;
$http = new HttpClient('https://api.smartresponsor.local', null);
$c = new OrderClient($http);
$o = $c->createOrder(1999, 'USD', 'cus_001');
$c->transition($o->id, 'confirm', Idempotency::key());
echo "OK\n";
