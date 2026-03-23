<?php

declare(strict_types=1);

namespace App\Subscriber\Order;

use App\Event\Order\OrderFullyRefundedEvent;
use App\Event\Order\OrderPartiallyRefundedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OrderRefundEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderPartiallyRefundedEvent::class => 'onPartial',
            OrderFullyRefundedEvent::class => 'onFull',
        ];
    }

    public function onPartial(OrderPartiallyRefundedEvent $event): void
    {
        $this->logger->info('Order partially refunded', [
            'orderId' => method_exists($event->order, 'getId') ? $event->order->getId() : null,
            'amount' => $event->refundAmount,
            'currency' => $event->currency,
        ]);
    }

    public function onFull(OrderFullyRefundedEvent $event): void
    {
        $this->logger->info('Order fully refunded', [
            'orderId' => method_exists($event->order, 'getId') ? $event->order->getId() : null,
            'totalRefunded' => $event->totalRefunded,
            'currency' => $event->currency,
        ]);
    }
}
