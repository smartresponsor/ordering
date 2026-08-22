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

$iterations = max(1, (int)(getenv('N') ?: ($argv[1] ?? 50)));
$mode = $argv[2] ?? 'demo';
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

$router = new ProviderRouter(
    [
        'stripe' => new StripeAdapter(),
        'alt' => new DummyAdapter('alt'),
    ],
    [
        'stripe' => new HealthProbe(220, 0.3, 0.019, 10000),
        'alt' => new HealthProbe(260, 0.2, 0.022, 5000),
    ],
    [
        'stripe' => 5.00,
        'alt' => 0.00,
    ],
    $policy,
    new CanarySwitch((int)($policyData['canary']['seed'] ?? 42)),
    new QuotaPolicy(),
    new CostPolicy(),
);

$canaryMode = 'canary' === $mode;
$selected = [];

for ($iteration = 0; $iteration < $iterations; ++$iteration) {
    $context = new RouteContext(sprintf('intent-aa-demo-%04d', $iteration + 1), 'us', 12.34, $canaryMode);
    $decision = $router->select($context);
    $provider = $decision->provider();
    $selected[$provider] = ($selected[$provider] ?? 0) + 1;
    echo sprintf('[%03d] provider=%s score=%0.6f%s', $iteration + 1, $provider, $decision->score(), PHP_EOL);
}

arsort($selected);
echo 'Summary:' . PHP_EOL;

foreach ($selected as $provider => $count) {
    echo sprintf('- %s: %d%s', $provider, $count, PHP_EOL);
}
