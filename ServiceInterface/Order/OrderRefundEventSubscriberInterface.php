<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use App\Event\Domain\Order\OrderFullyRefundedEvent;
use App\Event\Domain\Order\OrderPartiallyRefundedEvent;

interface OrderRefundEventSubscriberInterface
{
    public function onPartial(OrderPartiallyRefundedEvent $e): void;

    public function onFull(OrderFullyRefundedEvent $e): void;
}
