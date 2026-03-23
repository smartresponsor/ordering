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

$dir = __DIR__ . '/../var/key';
if (!is_dir($dir)) { mkdir($dir, 0777, true); }
$kid = 'kid-demo';

$config = [
  'private_key_bits' => 2048,
  'private_key_type' => OPENSSL_KEYTYPE_RSA,
];
$res = openssl_pkey_new($config);
openssl_pkey_export($res, $privatePem);
$detail = openssl_pkey_get_details($res);
$publicPem = $detail['key'];

file_put_contents($dir . '/private.pem', $privatePem);
file_put_contents($dir . '/public.pem', $publicPem);

$repo = new FileJwkRepository(__DIR__ . '/../var/jwk');
$key = new JwkKey($kid, 'RS256', 'RSA', $publicPem, $privatePem, true);
$repo->save($key);

echo "Generated JWK (kid=$kid) under var/jwk and PEM under var/key\n";
