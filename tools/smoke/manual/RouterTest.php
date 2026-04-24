<?php

declare(strict_types=1);

namespace App\Test\Smoke;

use App\Service\Transport\Order\DummyAdapter;
use App\Service\Transport\Order\ProviderRouter;
use App\Service\Transport\Order\StripeAdapter;
use App\ValueObject\Routing\Order\CanarySwitch;
use App\ValueObject\Routing\Order\CostPolicy;
use App\ValueObject\Routing\Order\HealthProbe;
use App\ValueObject\Routing\Order\ProviderPolicy;
use App\ValueObject\Routing\Order\QuotaPolicy;
use App\ValueObject\Routing\Order\RouteContext;
use PHPUnit\Framework\TestCase;

/**
 * Basic smoke coverage for provider routing.
 */
final class RouterTest extends TestCase
{
    public function testSelect(): void
    {
        $policy = new ProviderPolicy(0.5, 0.3, 0.2, 500, 5.0);
        $router = new ProviderRouter(
            ['stripe' => new StripeAdapter(), 'alt' => new DummyAdapter('alt')],
            ['stripe' => new HealthProbe(220, 0.3, 0.019, 10000), 'alt' => new HealthProbe(260, 0.2, 0.022, 5000)],
            ['stripe' => 0.0, 'alt' => 0.0],
            $policy,
            new CanarySwitch(42),
            new QuotaPolicy(),
            new CostPolicy(),
        );

        $decision = $router->select(new RouteContext('intent-abc', 'us', 10.0, false));
        self::assertContains($decision->provider(), ['stripe', 'alt']);
    }
}
