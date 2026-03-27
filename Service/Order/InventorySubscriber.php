<?php

declare(strict_types=1);

namespace App\Service\Order;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class InventorySubscriber implements EventSubscriberInterface
{
    public function __construct(private OrderInventoryReservationService $svc)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'order.placed' => 'onPlaced',
            'order.cancelled' => 'onCancelled',
            'order.refunded' => 'onRefunded',
        ];
    }

    public function onPlaced(object $event): void
    {
        $orderId = $event->orderId();
        foreach ($event->items() as $item) {
            $this->svc->reserveOrFail($orderId, $item['sku'], (int) $item['qty']);
        }
    }

    public function onCancelled(object $event): void
    {
        foreach ($event->items() as $item) {
            $this->svc->release($event->orderId(), $item['sku'], (int) $item['qty']);
        }
    }

    public function onRefunded(object $event): void
    {
        foreach ($event->items() as $item) {
            $this->svc->release($event->orderId(), $item['sku'], (int) $item['qty']);
        }
    }
}
