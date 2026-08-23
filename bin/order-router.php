#!/usr/bin/env php
<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

use App\Ordering\Service\Transport\Order\DummyAdapter;
use App\Ordering\Service\Transport\Order\ProviderRouter;
use App\Ordering\Service\Transport\Order\StripeAdapter;
use App\Ordering\ValueObject\Routing\Order\CanarySwitch;
use App\Ordering\ValueObject\Routing\Order\CostPolicy;
use App\Ordering\ValueObject\Routing\Order\HealthProbe;
use App\Ordering\ValueObject\Routing\Order\ProviderPolicy;
use App\Ordering\ValueObject\Routing\Order\QuotaPolicy;
use App\Ordering\ValueObject\Routing\Order\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

$mode = $argv[1] ?? 'demo';
$policyPath = __DIR__ . '/../config/router/policy.json';
$policyJson = file_get_contents($policyPath);

if (false === $policyJson) {
    fwrite(STDERR, sprintf('Unable to read router policy file: %s%s', $policyPath, PHP_EOL));
    exit(1);
}

$policyData = json_decode($policyJson, true, 512, JSON_THROW_ON_ERROR);

$policy = new ProviderPolicy(
    (float)$policyData['route']['weight_latency'],
    (float)$policyData['route']['weight_error'],
    (float)$policyData['route']['weight_cost'],
    (int)$policyData['threshold']['p95_ms'],
    (float)$policyData['threshold']['error_rate'],
);

$canarySwitch = new CanarySwitch((int)($policyData['canary']['seed'] ?? 42));
$quota = new QuotaPolicy();
$cost = new CostPolicy();

$adapter = [
    'stripe' => new StripeAdapter(),
    'alt' => new DummyAdapter('alt'),
];

$probe = [
    'stripe' => new HealthProbe(220, 0.3, 0.019, 10000),
    'alt' => new HealthProbe(260, 0.2, 0.022, 5000),
];

$canary = [
    'stripe' => 5.00,
    'alt' => 0.00,
];

$router = new ProviderRouter($adapter, $probe, $canary, $policy, $canarySwitch, $quota, $cost);

$context = new RouteContext('intent-aa-demo-0001', 'us', 12.34, 'canary' === $mode);
$decision = $router->select($context);

echo 'provider=' . $decision->provider() . ' score=' . number_format($decision->score(), 6) . PHP_EOL;
