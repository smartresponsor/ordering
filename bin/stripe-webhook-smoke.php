#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\Order\Payment\Stripe\StripeProvider;
use SmartResponsor\Order\Payment\Stripe\StripeClient;

$cfg = json_decode(file_get_contents(__DIR__.'/../config/payments/stripe.json'), true);
$payload = '{"type":"payment_intent.succeeded","data":{"object":{"id":"pi_123","amount_received":1999}}}';
$t = time();
$signed = $t . '.' . $payload;
$sig = hash_hmac('sha256', $signed, $cfg['webhook_secret']);
$header = "t=$t, v1=$sig";
$provider = new StripeProvider(new StripeClient($cfg['api_key']), $cfg['webhook_secret']);
echo $provider->verifyWebhook($payload, $header) ? "VALID\n" : "INVALID\n";
