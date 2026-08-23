<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Ordering\Service\Transport\Order\DummyAdapter;
use App\Ordering\Service\Transport\Order\ProviderRouter;
use App\Ordering\Service\Transport\Order\StripeAdapter;
use App\Ordering\ValueObject\Routing\Order\CanarySwitch;
use App\Ordering\ValueObject\Routing\Order\CostPolicy;
use App\Ordering\ValueObject\Routing\Order\HealthProbe;
use App\Ordering\ValueObject\Routing\Order\ProviderPolicy;
use App\Ordering\ValueObject\Routing\Order\QuotaPolicy;
use App\Ordering\ValueObject\Routing\Order\RouteContext;
use PHPUnit\Framework\TestCase;

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
