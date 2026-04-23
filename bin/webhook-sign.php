#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Service\Webhook\Order\WebhookSignerHmac;
use App\Service\Webhook\Order\WebhookSignerRsa;

$alg = $argv[1] ?? 'hmac';
$payload = stream_get_contents(STDIN);

if ('hmac' === $alg) {
    $secret = $argv[2] ?? 'secret';
    $signer = new WebhookSignerHmac();
    echo $signer->sign($payload, $secret) . '
';
    exit(0);
}

if ('rsa' === $alg) {
    $privatePemPath = $argv[2] ?? __DIR__ . '/../var/key/private.pem';
    $kid = $argv[3] ?? 'kid-demo';
    $privatePem = @file_get_contents($privatePemPath);
    if (false === $privatePem || '' === $privatePem) {
        fwrite(STDERR, "Private key not found: {$privatePemPath}
");
        exit(1);
    }

    $signer = new WebhookSignerRsa();
    echo $signer->sign($payload, $privatePem, $kid) . '
';
    exit(0);
}

fwrite(STDERR, 'Usage: webhook-sign.php hmac <secret> | rsa <private.pem> <kid>
');
exit(2);
