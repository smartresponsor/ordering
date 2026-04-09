<?php

declare(strict_types=1);

namespace App\Subscriber\Event\Order;

use App\ServiceInterface\Inventory\Order\InventoryReservationServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class InventorySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ?InventoryReservationServiceInterface $svc = null,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'order.placed' => 'onPlaced',
            'order.cancelled' => 'onCancelled',
            'order.refunded' => 'onRefunded',
            'App\\Event\\Domain\\Order\\OrderPlacedEvent' => 'onPlaced',
            'App\\Event\\Domain\\Order\\OrderCancelledEvent' => 'onCancelled',
        ];
    }

    public function onPlaced(object $event): void
    {
        if (!method_exists($event, 'orderId') || !method_exists($event, 'items')) {
            return;
        }

        $orderId = (string) $event->orderId();
        $items = $event->items();
        if (!is_iterable($items)) {
            return;
        }

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $sku = $item['sku'] ?? null;
            $qty = $item['qty'] ?? null;
            if (!is_scalar($sku) || !is_scalar($qty)) {
                continue;
            }

            $this->svc?->reserveOrFail($orderId, (string) $sku, (int) $qty);
        }
    }

    public function onCancelled(object $event): void
    {
        $this->releaseFromEvent($event);
    }

    public function onRefunded(object $event): void
    {
        $this->releaseFromEvent($event);
    }

    private function releaseFromEvent(object $event): void
    {
        if (!method_exists($event, 'orderId') || !method_exists($event, 'items')) {
            return;
        }

        $orderId = (string) $event->orderId();
        $items = $event->items();
        if (!is_iterable($items)) {
            return;
        }

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $sku = $item['sku'] ?? null;
            $qty = $item['qty'] ?? null;
            if (!is_scalar($sku) || !is_scalar($qty)) {
                continue;
            }

            $this->svc?->release($orderId, (string) $sku, (int) $qty);
        }
    }
}
