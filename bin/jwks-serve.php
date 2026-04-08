#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use App\Service\Security\Order\FileJwkRepository;
use App\Service\Security\Order\JwksIssuer;

$repo = new FileJwkRepository(__DIR__.'/../var/jwk');
$issuer = new JwksIssuer($repo);

echo $issuer->issue()."
";
