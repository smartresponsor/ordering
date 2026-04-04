<?php

declare(strict_types=1);

namespace App\ServiceInterface\Security\Order;

use App\Event\Domain\Order\OrderDeliveredEvent;
use App\Event\Domain\Order\OrderReturnWindowExpiredEvent;

interface OrderShipmentSubscriberInterface
{
    public function onDelivered(OrderDeliveredEvent $e): void;

    public function onReturnExpired(OrderReturnWindowExpiredEvent $e): void;
}
