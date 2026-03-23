#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\OrderSDK\Http\HttpClient;
use SmartResponsor\OrderSDK\Client\OrderClient;
use SmartResponsor\OrderSDK\Util\Idempotency;

$base = getenv('BASE') ?: 'https://api.smartresponsor.local';
$token = getenv('TOKEN') ?: null;
$http = new HttpClient($base, $token);
$client = new OrderClient($http);

$cmd = $argv[1] ?? 'help';
if ($cmd === 'create-confirm') {
    $o = $client->createOrder(1999, 'USD', 'cus_001');
    $client->transition($o->id, 'confirm', Idempotency::key());
    fwrite(STDOUT, json_encode(['orderId'=>$o->id,'status'=>'confirmed'])."\n");
} else {
    fwrite(STDOUT, "Usage: sr-order.php create-confirm\n");
}
