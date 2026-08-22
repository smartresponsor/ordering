<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Subscriber\Order;

use App\Ordering\Event\Domain\Order\OrderDeliveredEvent;
use App\Ordering\Event\Domain\Order\OrderReturnWindowExpiredEvent;

interface OrderShipmentSubscriberInterface
{
    public function onDelivered(OrderDeliveredEvent $e): void;

    public function onReturnExpired(OrderReturnWindowExpiredEvent $e): void;
}
