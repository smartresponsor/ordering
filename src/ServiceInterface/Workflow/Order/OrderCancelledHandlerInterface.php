<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Workflow\Order;

use App\Ordering\Event\Domain\Order\OrderCancelledEvent;

interface OrderCancelledHandlerInterface
{
    public function __invoke(OrderCancelledEvent $event): void;
}
