<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use App\Event\Order\OrderChargebackIssuedEvent;
use App\Event\Order\OrderDisputeOpenedEvent;
use App\Event\Order\OrderDisputeResolvedEvent;

interface OrderDisputeSubscriberInterface
{
    public function onOpened(OrderDisputeOpenedEvent $e): void;

    public function onResolved(OrderDisputeResolvedEvent $e): void;

    public function onChargeback(OrderChargebackIssuedEvent $e): void;
}
