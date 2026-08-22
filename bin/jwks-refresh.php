#!/usr/bin/env php
<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

use App\Ordering\Service\Security\Order\FileJwkRepository;
use App\Ordering\Service\Security\Order\JwksIssuer;

require __DIR__ . '/../vendor/autoload.php';

$keyDir = __DIR__ . '/../var/security/jwk';
$outputFile = __DIR__ . '/../var/security/jwks.json';

$repository = new FileJwkRepository($keyDir);
$issuer = new JwksIssuer($repository);
$jwkSet = $issuer->issue();

if (!is_dir(dirname($outputFile))) {
    mkdir(dirname($outputFile), 0775, true);
}

file_put_contents($outputFile, $jwkSet);

echo sprintf('jwks refreshed: %s%s', $outputFile, PHP_EOL);
