<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use App\Service\Order\RouteContext;
use App\Service\Order\RouteDecision;

interface ProviderRouterInterface
{
    public function select(RouteContext $context): RouteDecision;
}
