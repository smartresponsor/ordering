#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use App\Service\Webhook\Order\WebhookSignerHmac;
use App\Service\Webhook\Order\WebhookSignerRsa;
use App\Service\Webhook\Order\WebhookVerifierHmac;
use App\Service\Webhook\Order\WebhookVerifierRsa;

$algorithm = $argv[1] ?? 'hmac';
$payload = $argv[2] ?? '{"event":"order.created","id":"ord_demo_1"}';

if ('hmac' === $algorithm) {
    $secret = $argv[3] ?? 'secret';
    $signer = new WebhookSignerHmac();
    $verifier = new WebhookVerifierHmac();
    $signature = $signer->sign($payload, $secret);
    $verified = $verifier->verify($payload, $secret, $signature);

    fwrite(STDOUT, $verified ? "VALID\n" : "INVALID\n");
    fwrite(STDOUT, sprintf("signature=%s\n", $signature));
    exit($verified ? 0 : 1);
}

if ('rsa' === $algorithm) {
    $privatePemPath = $argv[3] ?? __DIR__.'/../var/key/private.pem';
    $publicPemPath = $argv[4] ?? __DIR__.'/../var/key/public.pem';
    $kid = $argv[5] ?? 'kid-demo';

    $privatePem = @file_get_contents($privatePemPath);
    if (false === $privatePem || '' === $privatePem) {
        fwrite(STDERR, sprintf("Private key not found: %s\n", $privatePemPath));
        exit(1);
    }

    $publicPem = @file_get_contents($publicPemPath);
    if (false === $publicPem || '' === $publicPem) {
        fwrite(STDERR, sprintf("Public key not found: %s\n", $publicPemPath));
        exit(1);
    }

    $signer = new WebhookSignerRsa();
    $verifier = new WebhookVerifierRsa();
    $signature = $signer->sign($payload, (string) $privatePem, $kid);
    $verified = $verifier->verify($payload, (string) $publicPem, $signature);

    fwrite(STDOUT, $verified ? "VALID\n" : "INVALID\n");
    fwrite(STDOUT, sprintf("signature=%s\n", $signature));
    exit($verified ? 0 : 1);
}

fwrite(STDERR, "Usage: webhook-smoke.php [hmac <payload> <secret> | rsa <payload> <private.pem> <public.pem> <kid>]\n");
exit(2);
