<?php

declare(strict_types=1);

namespace App\Service\Subscriber\Order;

use App\Event\Domain\Order\OrderFullyRefundedEvent;
use App\Event\Domain\Order\OrderPartiallyRefundedEvent;
use App\ServiceInterface\Subscriber\Order\OrderRefundEventSubscriberInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class OrderRefundEventSubscriber implements OrderRefundEventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    #[AsEventListener(event: OrderPartiallyRefundedEvent::class)]
    public function onPartial(OrderPartiallyRefundedEvent $e): void
    {
        $this->logger->info('Order partially refunded', [
            'orderId' => $e->order->getId(),
            'amount' => $e->refundAmount,
            'currency' => $e->currency,
        ]);
    }

    #[AsEventListener(event: OrderFullyRefundedEvent::class)]
    public function onFull(OrderFullyRefundedEvent $e): void
    {
        $this->logger->info('Order fully refunded', [
            'orderId' => $e->order->getId(),
            'totalRefunded' => $e->totalRefunded,
            'currency' => $e->currency,
        ]);
    }
}
