<?php

declare(strict_types=1);

namespace App\ReadModel\Subscriber;

use App\Event\Order\OrderPlacedEvent;
use App\Event\Order\OrderShippedEvent;
use App\ReadModel\Service\OrderReadModelProjector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class OrderEventProjectorSubscriber implements EventSubscriberInterface
{
    public function __construct(private OrderReadModelProjector $projector)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderPlacedEvent::class => 'onOrderPlaced',
            OrderShippedEvent::class => 'onOrderShipped',
        ];
    }

    public function onOrderPlaced(OrderPlacedEvent $event): void
    {
        $this->projector->project((string) $event->orderId, 'placed');
    }

    public function onOrderShipped(OrderShippedEvent $event): void
    {
        $this->projector->project((string) $event->orderId, 'shipped');
    }
}
