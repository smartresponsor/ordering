#!/usr/bin/env php
<?php
declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

namespace SmartResponsor\Order;

require __DIR__ . '/../vendor/autoload.php';

$alg = $argv[1] ?? 'hmac';
if ($alg === 'hmac') {
    $secret = $argv[2] ?? 'secret';
    $payload = stream_get_contents(STDIN);
    $signer = new WebhookSignerHmac();
    echo $signer->sign($payload, $secret) . "\n";
    exit(0);
}
if ($alg === 'rsa') {
    $privatePemPath = $argv[2] ?? __DIR__ . '/../var/key/private.pem';
    $kid = $argv[3] ?? 'kid-demo';
    $payload = stream_get_contents(STDIN);
    $signer = new WebhookSignerRsa();
    $privatePem = file_get_contents($privatePemPath);
    echo $signer->sign($payload, (string)$privatePem, $kid) . "\n";
    exit(0);
}
fwrite(STDERR, "Usage: webhook-sign.php hmac <secret> | rsa <private.pem> <kid>\n");
exit(2);
