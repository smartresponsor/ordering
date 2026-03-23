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

$oldKid = $argv[1] ?? 'kid-demo';
$newKid = $argv[2] ?? 'kid-next';

$repo = new FileJwkRepository(__DIR__ . '/../var/jwk');

// create new pair
$config = [
  'private_key_bits' => 2048,
  'private_key_type' => OPENSSL_KEYTYPE_RSA,
];
$res = openssl_pkey_new($config);
openssl_pkey_export($res, $privatePem);
$detail = openssl_pkey_get_details($res);
$publicPem = $detail['key'];

$mgr = new KeyRotationManager($repo, new SecretRotationPolicy());
$mgr->rotate($oldKid, $newKid, $publicPem, $privatePem);
echo "Rotated: old=$oldKid -> new=$newKid\n";
