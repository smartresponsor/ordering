<?php

declare(strict_types=1);

namespace App\Service\Analytics\Order;

use App\Event\Domain\Order\OrderPlacedEvent;
use App\Event\Domain\Order\OrderRefundedEvent;
use App\ServiceInterface\Analytics\Order\MetricsSubscriberInterface;
use App\ServiceInterface\Analytics\Order\OrderMetricsSubscriberInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class MetricsSubscriber implements MetricsSubscriberInterface, OrderMetricsSubscriberInterface
{
    public function __construct(private readonly MetricsProjectionService $proj)
    {
    }

    #[AsEventListener(event: OrderPlacedEvent::class)]
    public function onPlaced(OrderPlacedEvent $e): void
    {
        $this->proj->projectOrderPlaced($e->order, $e->order->getTotalAmount(), $e->order->getVendorId(), new \DateTimeImmutable('now'));
    }

    #[AsEventListener(event: OrderRefundedEvent::class)]
    public function onRefunded(OrderRefundedEvent $e): void
    {
        $amount = is_object($e->amount) && property_exists($e->amount, 'amount') ? (string) $e->amount->amount : (string) $e->amount;
        $this->proj->projectRefund($amount, new \DateTimeImmutable('now'));
    }
}
