<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Transport\Order;

use App\Ordering\ValueObject\Routing\Order\RouteContext;
use App\Ordering\ValueObject\Routing\Order\RouteDecision;

interface ProviderRouterInterface
{
    public function select(RouteContext $context): RouteDecision;
}
