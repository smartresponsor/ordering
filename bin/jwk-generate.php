#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Service\Security\Order\FileJwkRepository;
use App\ValueObject\Security\Order\JwkKey;

$dir = __DIR__ . '/../var/key';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$kid = $argv[1] ?? 'kid-demo';
$config = [
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
];

$res = openssl_pkey_new($config);
if (false === $res) {
    fwrite(STDERR, 'Unable to generate RSA key pair
');
    exit(1);
}

$privatePem = '';
openssl_pkey_export($res, $privatePem);
$detail = openssl_pkey_get_details($res);
$publicPem = is_array($detail) ? (string)($detail['key'] ?? '') : '';

if ('' === $privatePem || '' === $publicPem) {
    fwrite(STDERR, 'Unable to export RSA key pair
');
    exit(1);
}

file_put_contents($dir . '/private.pem', $privatePem);
file_put_contents($dir . '/public.pem', $publicPem);

$repo = new FileJwkRepository(__DIR__ . '/../var/jwk');
$key = new JwkKey($kid, 'RS256', 'RSA', $publicPem, $privatePem, true);
$repo->save($key);

echo "Generated JWK (kid={$kid}) under var/jwk and PEM under var/key
";
