<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ServiceInterface\Order\EmailSubscriberInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'order.event', method: 'onOrderEvent')]
final class EmailSubscriber implements EmailSubscriberInterface
{
    public function onOrderEvent(array $payload): void
    {
        // send emails on placed/paid/shipped events (stub)
    }
}
