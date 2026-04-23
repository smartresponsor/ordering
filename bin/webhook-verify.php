#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Service\Webhook\Order\WebhookVerifierHmac;
use App\Service\Webhook\Order\WebhookVerifierRsa;

$alg = $argv[1] ?? 'hmac';
$payload = $argv[3] ?? '{"ok":true}';
$input = stream_get_contents(STDIN);

if ('hmac' === $alg) {
    $secret = $argv[2] ?? 'secret';
    $signature = trim($input);
    $verifier = new WebhookVerifierHmac();
    echo $verifier->verify($payload, $secret, $signature) ? 'OK
' : 'FAIL
';
    exit(0);
}

if ('rsa' === $alg) {
    $publicPemPath = $argv[2] ?? __DIR__ . '/../var/key/public.pem';
    $publicPem = @file_get_contents($publicPemPath);
    if (false === $publicPem || '' === $publicPem) {
        fwrite(STDERR, "Public key not found: {$publicPemPath}
");
        exit(1);
    }

    $verifier = new WebhookVerifierRsa();
    echo $verifier->verify($payload, $publicPem, $input) ? 'OK
' : 'FAIL
';
    exit(0);
}

fwrite(STDERR, 'Usage: webhook-verify.php hmac <secret> [payload] | rsa <public.pem> [payload]
');
exit(2);
