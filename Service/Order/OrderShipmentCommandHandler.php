<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Message\Order\OrderShipmentCommand;
use App\Service\Order\Adapter\Shipment\CarrierInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OrderShipmentCommandHandler
{
    public function __construct(
        private OrderShipmentService $service,
        private TransactionalEventPublisher $publisher,
        private CarrierInterface $carrier,
    ) {
    }

    public function __invoke(OrderShipmentCommand $cmd): void
    {
        $tracking = $this->carrier->ship($cmd->orderId, $cmd->carrier);
        $this->service->markShipped($cmd->orderId, $tracking);
        $this->publisher->publish('order.shipped', ['orderId' => $cmd->orderId, 'carrier' => $cmd->carrier, 'tracking' => $tracking]);
    }
}
