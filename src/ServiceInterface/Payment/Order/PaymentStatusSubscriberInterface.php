<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Payment\Order;

use App\Event\Domain\Order\OrderFullyPaidEvent;
use App\Event\Domain\Order\OrderPartiallyPaidEvent;
use App\Event\Domain\Order\OrderPartiallyRefundedEvent;

interface PaymentStatusSubscriberInterface
{
    public function onPartiallyPaid(OrderPartiallyPaidEvent $e): void;

    public function onFullyPaid(OrderFullyPaidEvent $e): void;

    public function onPartiallyRefunded(OrderPartiallyRefundedEvent $e): void;
}
