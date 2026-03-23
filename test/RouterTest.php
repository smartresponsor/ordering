<?php
declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

namespace SmartResponsor\Order;

use PHPUnit\Framework\TestCase;
use SmartResponsor\Order\ProviderAdapter\StripeAdapter;
use SmartResponsor\Order\ProviderAdapter\DummyAdapter;

final class RouterTest extends TestCase
{
    public function testSelect()
    {
        $policy = new ProviderPolicy(0.5, 0.3, 0.2, 500, 5.0);
        $router = new ProviderRouter(
            ['stripe' => new StripeAdapter(), 'alt' => new DummyAdapter('alt')],
            ['stripe' => new HealthProbe(220, 0.3, 0.019, 10000), 'alt' => new HealthProbe(260, 0.2, 0.022, 5000)],
            ['stripe' => 0.0, 'alt' => 0.0],
            $policy,
            new CanarySwitch(42),
            new QuotaPolicy(),
            new CostPolicy()
        );
        $ctx = new RouteContext('intent-abc', 'us', 10.0, false);
        $d = $router->select($ctx);
        $this->assertTrue(in_array($d->provider(), ['stripe','alt'], true));
    }
}
