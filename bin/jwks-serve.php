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

$repo = new FileJwkRepository(__DIR__ . '/../var/jwk');
$issuer = new JwksIssuer($repo);

echo $issuer->issue() . "\n";
