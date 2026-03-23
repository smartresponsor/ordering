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
    $sig = trim(stream_get_contents(STDIN));
    $payload = '{"ok":true}'; // demo payload; pipe actual payload if needed
    $verifier = new WebhookVerifierHmac();
    echo $verifier->verify($payload, $secret, $sig) ? "OK\n" : "FAIL\n";
    exit(0);
}
if ($alg === 'rsa') {
    $publicPemPath = $argv[2] ?? __DIR__ . '/../var/key/public.pem';
    $env = stream_get_contents(STDIN);
    $payload = '{"ok":true}';
    $verifier = new WebhookVerifierRsa();
    $publicPem = file_get_contents($publicPemPath);
    echo $verifier->verify($payload, (string)$publicPem, $env) ? "OK\n" : "FAIL\n";
    exit(0);
}
fwrite(STDERR, "Usage: webhook-verify.php hmac <secret> | rsa <public.pem>\n");
exit(2);
