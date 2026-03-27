<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use App\ValueObject\Order\RouteContext;
use App\ValueObject\Order\RouteDecision;

interface ProviderRouterInterface
{
    public function select(RouteContext $context): RouteDecision;
}
