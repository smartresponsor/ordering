<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

interface WebhookDispatcherInterface
{
    public function dispatch(object $event): void;
}
