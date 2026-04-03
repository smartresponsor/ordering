<?php

declare(strict_types=1);

namespace App\Subscriber\Event\Order;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class EmailSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'App\Event\Domain\Order\OrderPaidEvent' => 'onPaid',
            'App\Event\Domain\Order\OrderShippedEvent' => 'onShipped',
        ];
    }

    public function onPaid(object $event): void
    { /* send email */
    }

    public function onShipped(object $event): void
    { /* send email */
    }
}
