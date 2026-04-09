<?php

declare(strict_types=1);

namespace App\Service\Payment\Order;

use App\Event\Domain\Order\OrderFullyPaidEvent;
use App\Event\Domain\Order\OrderPartiallyPaidEvent;
use App\Event\Domain\Order\OrderPartiallyRefundedEvent;
use App\ServiceInterface\Payment\Order\PaymentStatusSubscriberInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PaymentStatusSubscriber implements EventSubscriberInterface, PaymentStatusSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderPartiallyPaidEvent::class => 'onPartiallyPaid',
            OrderFullyPaidEvent::class => 'onFullyPaid',
            OrderPartiallyRefundedEvent::class => 'onPartiallyRefunded',
        ];
    }

    public function onPartiallyPaid(OrderPartiallyPaidEvent $e): void
    {
        $this->logger->info('Order partially paid', ['order' => $e->order->getId(), 'paid' => $e->paidAmount, 'balance' => $e->balanceAmount]);
    }

    public function onFullyPaid(OrderFullyPaidEvent $e): void
    {
        $this->logger->info('Order fully paid', ['order' => $e->order->getId()]);
    }

    public function onPartiallyRefunded(OrderPartiallyRefundedEvent $e): void
    {
        $this->logger->info('Order partially refunded', ['order' => $e->order->getId(), 'amount' => $e->refundAmount, 'balance' => $e->balanceAmount]);
    }
}
