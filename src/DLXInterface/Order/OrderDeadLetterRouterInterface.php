<?php

declare(strict_types=1);

namespace App\DLXInterface\Order;

interface OrderDeadLetterRouterInterface
{
    public function route(string $queue): string;
}
