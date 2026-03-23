<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order\Http;

interface SinkRouterInterface
{
    public function route(array $payload): void;
}
