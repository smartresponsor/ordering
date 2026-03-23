<?php

declare(strict_types=1);

namespace App\Subscriber\Order;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class EmailSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'App\Event\Order\OrderPaidEvent' => 'onPaid',
            'App\Event\Order\OrderShippedEvent' => 'onShipped',
        ];
    }

    public function onPaid(object $event): void
    { /* send email */
    }

    public function onShipped(object $event): void
    { /* send email */
    }
}
