<?php

declare(strict_types=1);

namespace App\MessageHandler\Order;

use App\Message\Order\OrderShipmentCommand;
use App\Service\Order\Adapter\Shipment\CarrierInterface;
use App\Service\Order\ShipmentService;
use App\Service\Order\TransactionalEventPublisher;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderShipmentCommandHandler
{
    public function __construct(
        private ShipmentService $service,
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
