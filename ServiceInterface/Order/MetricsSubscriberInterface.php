<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

use App\Event\Domain\Order\OrderPlacedEvent;
use App\Event\Domain\Order\OrderRefundedEvent;

interface MetricsSubscriberInterface
{
    public function onPlaced(OrderPlacedEvent $e): void;

    public function onRefunded(OrderRefundedEvent $e): void;
}
