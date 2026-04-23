#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Service\Webhook\Order\WebhookSignerHmac;
use App\Service\Webhook\Order\WebhookVerifierHmac;

$configPath = __DIR__ . '/../config/payments/stripe.json';
$configRaw = @file_get_contents($configPath);
$config = is_string($configRaw) ? json_decode($configRaw, true) : null;

if (!is_array($config)) {
    fwrite(STDERR, "Stripe config not found or invalid: {$configPath}\n");
    exit(1);
}

$payload = $argv[1] ?? '{"type":"payment_intent.succeeded","data":{"object":{"id":"pi_123","amount_received":1999}}}';
$secret = (string)($config['webhook_secret'] ?? '');
if ('' === $secret) {
    fwrite(STDERR, "Missing webhook_secret in {$configPath}\n");
    exit(1);
}

$signer = new WebhookSignerHmac();
$verifier = new WebhookVerifierHmac();
$signature = $signer->sign($payload, $secret);
$verified = $verifier->verify($payload, $secret, $signature);

echo $verified ? "VALID\n" : "INVALID\n";
echo "signature={$signature}\n";
