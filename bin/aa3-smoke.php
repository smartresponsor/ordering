#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Service\Transport\Order\DummyAdapter;
use App\Service\Transport\Order\ProviderRouter;
use App\Service\Transport\Order\StripeAdapter;
use App\ValueObject\Routing\Order\CanarySwitch;
use App\ValueObject\Routing\Order\CostPolicy;
use App\ValueObject\Routing\Order\HealthProbe;
use App\ValueObject\Routing\Order\ProviderPolicy;
use App\ValueObject\Routing\Order\QuotaPolicy;
use App\ValueObject\Routing\Order\RouteContext;

$router = new ProviderRouter(
    [
        'stripe' => new StripeAdapter(),
        'dummy' => new DummyAdapter('dummy'),
    ],
    [
        'stripe' => new HealthProbe(120, 0.02, 0.029),
        'dummy' => new HealthProbe(240, 0.05, 0.010),
    ],
    [
        'stripe' => 10.0,
        'dummy' => 100.0,
    ],
    new ProviderPolicy(0.5, 0.3, 0.2, 400, 0.10),
    new CanarySwitch(),
    new QuotaPolicy(1000),
    new CostPolicy(0.005, 0.050),
);

echo "[AA3 smoke] trying 200 route selections...\n";
$summary = [];

for ($i = 0; $i < 200; ++$i) {
    $context = new RouteContext('ord_' . $i, 'us', 19.99, false);
    $decision = $router->select($context);
    $provider = $decision->provider();
    $summary[$provider] = ($summary[$provider] ?? 0) + 1;
}

ksort($summary);
echo json_encode(['iterations' => 200, 'providers' => $summary], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n";
