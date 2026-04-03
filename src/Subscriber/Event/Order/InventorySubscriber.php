<?php

declare(strict_types=1);

namespace App\Subscriber\Event\Order;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class InventorySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'App\Event\Domain\Order\OrderPlacedEvent' => 'onPlaced',
            'App\Event\Domain\Order\OrderCancelledEvent' => 'onCancelled',
        ];
    }

    public function onPlaced(object $event): void
    { /* reserve stock */
    }

    public function onCancelled(object $event): void
    { /* release stock */
    }
}
