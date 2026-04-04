<?php

declare(strict_types=1);

namespace App\ServiceInterface\Security\Order;

use App\ValueObject\Routing\Order\RouteContext;
use App\ValueObject\Routing\Order\RouteDecision;

interface ProviderRouterInterface
{
    public function select(RouteContext $context): RouteDecision;
}
