<?php

declare(strict_types=1);

namespace App\Subscriber\Event\Order;

use App\Service\Shipment\Order\ShipmentService;
use App\Service\Order\TransactionalEventPublisher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class ShipmentStatusListener implements EventSubscriberInterface
{
    public function __construct(private ShipmentService $service, private TransactionalEventPublisher $publisher)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return ['shipment.status' => 'onStatus'];
    }

    public function onStatus(object $event): void
    {
        // $event должен иметь orderId, tracking, status
        if (method_exists($event, 'orderId') && method_exists($event, 'tracking') && method_exists($event, 'status')) {
            if ('shipped' === $event->status()) {
                $this->service->markShipped($event->orderId(), $event->tracking());
                $this->publisher->publish('order.shipped', ['orderId' => $event->orderId(), 'tracking' => $event->tracking()]);
            }
        }
    }
}
