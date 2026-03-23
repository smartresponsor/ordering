<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ServiceInterface\Order\AnalyticsSubscriberInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'order.event', method: 'onOrderEvent')]
final class AnalyticsSubscriber implements AnalyticsSubscriberInterface
{
    public function onOrderEvent(array $payload): void
    {
        // collect metrics (stub)
    }
}
