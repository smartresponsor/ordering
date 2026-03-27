<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use App\Event\Order\OrderFullyRefundedEvent;
use App\Event\Order\OrderPartiallyRefundedEvent;

interface OrderRefundEventSubscriberInterface
{
    public function onPartial(OrderPartiallyRefundedEvent $e): void;

    public function onFull(OrderFullyRefundedEvent $e): void;
}
