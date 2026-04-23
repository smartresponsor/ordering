#!/usr/bin/env php
<?php

declare(strict_types=1);

$privateKeyPath = __DIR__ . '/../tests/keys/test-private.pem';
$privateKey = @file_get_contents($privateKeyPath);
if (false === $privateKey || '' === $privateKey) {
    fwrite(STDERR, sprintf("Private key not found: %s\n", $privateKeyPath));
    exit(1);
}

$kid = $argv[1] ?? 'test1';
$issuer = $argv[2] ?? 'https://issuer.example';
$audience = $argv[3] ?? 'smartresponsor';
$subject = $argv[4] ?? 'user_123';
$ttl = (int)($argv[5] ?? 300);
$nbfShift = (int)($argv[6] ?? 0);

$header = [
    'typ' => 'JWT',
    'alg' => 'RS256',
    'kid' => $kid,
];

$now = time();
$payload = [
    'iss' => $issuer,
    'aud' => $audience,
    'sub' => $subject,
    'iat' => $now,
    'nbf' => $now + $nbfShift,
    'exp' => $now + $ttl,
];

$encodedHeader = rtrim(strtr(base64_encode((string)json_encode($header, JSON_THROW_ON_ERROR)), '+/', '-_'), '=');
$encodedPayload = rtrim(strtr(base64_encode((string)json_encode($payload, JSON_THROW_ON_ERROR)), '+/', '-_'), '=');
$signingInput = $encodedHeader . '.' . $encodedPayload;

$signature = '';
if (!openssl_sign($signingInput, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
    fwrite(STDERR, "Unable to sign JWT payload.\n");
    exit(1);
}

$encodedSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
fwrite(STDOUT, $signingInput . '.' . $encodedSignature . PHP_EOL);
