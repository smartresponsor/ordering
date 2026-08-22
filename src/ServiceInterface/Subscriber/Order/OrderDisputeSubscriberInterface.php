<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Subscriber\Order;

use App\Ordering\Event\Domain\Order\OrderChargebackIssuedEvent;
use App\Ordering\Event\Domain\Order\OrderDisputeOpenedEvent;
use App\Ordering\Event\Domain\Order\OrderDisputeResolvedEvent;

interface OrderDisputeSubscriberInterface
{
    public function onOpened(OrderDisputeOpenedEvent $e): void;

    public function onResolved(OrderDisputeResolvedEvent $e): void;

    public function onChargeback(OrderChargebackIssuedEvent $e): void;
}
