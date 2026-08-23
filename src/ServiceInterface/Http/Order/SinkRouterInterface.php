<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Http\Order;

interface SinkRouterInterface
{
    public function route(array $payload): void;
}
