<?php

declare(strict_types=1);

namespace App\Ordering\DLXInterface\Routing\Order;

interface OrderDeadLetterRouterInterface
{
    public function route(string $queue): string;
}
