#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Service\Security\Order\FileJwkRepository;
use App\Service\Security\Order\KeyRotationManager;
use App\Service\Security\Order\SecretRotationPolicy;

$oldKid = $argv[1] ?? 'kid-demo';
$newKid = $argv[2] ?? 'kid-next';

$repo = new FileJwkRepository(__DIR__ . '/../var/jwk');
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

$manager = new KeyRotationManager($repo, new SecretRotationPolicy());
$manager->rotate($oldKid, $newKid, $publicPem, $privatePem);

echo "Rotated: old={$oldKid} -> new={$newKid}
";
