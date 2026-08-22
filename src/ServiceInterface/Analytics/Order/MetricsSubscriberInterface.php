<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Ordering\ServiceInterface\Analytics\Order;

use App\Ordering\Event\Domain\Order\OrderPlacedEvent;
use App\Ordering\Event\Domain\Order\OrderRefundedEvent;

interface MetricsSubscriberInterface
{
    public function onPlaced(OrderPlacedEvent $e): void;

    public function onRefunded(OrderRefundedEvent $e): void;
}
