<?php

declare(strict_types=1);

namespace App\Subscriber\Event\Order;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OrderWorkflowSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [];
    }
}
