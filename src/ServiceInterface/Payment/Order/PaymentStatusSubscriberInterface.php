<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Ordering\ServiceInterface\Payment\Order;

use App\Ordering\Event\Domain\Order\OrderFullyPaidEvent;
use App\Ordering\Event\Domain\Order\OrderPartiallyPaidEvent;
use App\Ordering\Event\Domain\Order\OrderPartiallyRefundedEvent;

interface PaymentStatusSubscriberInterface
{
    public function onPartiallyPaid(OrderPartiallyPaidEvent $e): void;

    public function onFullyPaid(OrderFullyPaidEvent $e): void;

    public function onPartiallyRefunded(OrderPartiallyRefundedEvent $e): void;
}
