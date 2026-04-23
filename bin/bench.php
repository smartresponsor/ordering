#!/usr/bin/env php
<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

use App\Service\Transport\Order\DummyAdapter;
use App\Service\Transport\Order\ProviderRouter;
use App\Service\Transport\Order\StripeAdapter;
use App\ValueObject\Routing\Order\CanarySwitch;
use App\ValueObject\Routing\Order\CostPolicy;
use App\ValueObject\Routing\Order\HealthProbe;
use App\ValueObject\Routing\Order\ProviderPolicy;
use App\ValueObject\Routing\Order\QuotaPolicy;
use App\ValueObject\Routing\Order\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

$iterations = max(1, (int)($argv[1] ?? 1000));
$canaryMode = in_array('--canary', $argv, true);

$router = new ProviderRouter(
    [
        'stripe' => new StripeAdapter(),
        'alt' => new DummyAdapter('alt'),
    ],
    [
        'stripe' => new HealthProbe(220, 0.003, 0.019, 10000),
        'alt' => new HealthProbe(260, 0.002, 0.022, 5000),
    ],
    [
        'stripe' => 5.0,
        'alt' => 0.0,
    ],
    new ProviderPolicy(0.45, 0.35, 0.20, 300, 0.01),
    new CanarySwitch(42),
    new QuotaPolicy(),
    new CostPolicy(),
);

$counts = [];
$start = microtime(true);

for ($i = 0; $i < $iterations; ++$i) {
    $context = new RouteContext(
        sprintf('bench-intent-%06d', $i),
        'us',
        12.34,
        $canaryMode,
    );

    $decision = $router->select($context);
    $provider = $decision->provider();
    $counts[$provider] = ($counts[$provider] ?? 0) + 1;
}

$seconds = microtime(true) - $start;

echo json_encode([
        'iterations' => $iterations,
        'seconds' => $seconds,
        'routes_per_second' => $iterations / max(0.001, $seconds),
        'canary' => $canaryMode,
        'providers' => $counts,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
