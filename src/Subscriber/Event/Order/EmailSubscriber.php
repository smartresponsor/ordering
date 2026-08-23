<?php

declare(strict_types=1);

namespace App\Ordering\Subscriber\Event\Order;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class EmailSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'App\Ordering\Event\Domain\Order\OrderPaidEvent' => 'onPaid',
            'App\Ordering\Event\Domain\Order\OrderShippedEvent' => 'onShipped',
        ];
    }

    public function onPaid(object $event): void
    { /* send email */
    }

    public function onShipped(object $event): void
    { /* send email */
    }
}
