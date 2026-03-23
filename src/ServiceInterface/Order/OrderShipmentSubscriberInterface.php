<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use App\Event\Order\OrderDeliveredEvent;
use App\Event\Order\OrderReturnWindowExpiredEvent;

interface OrderShipmentSubscriberInterface
{
    public function onDelivered(OrderDeliveredEvent $e): void;

    public function onReturnExpired(OrderReturnWindowExpiredEvent $e): void;
}
