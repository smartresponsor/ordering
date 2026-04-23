<?php

declare(strict_types=1);

namespace App\Service\Analytics\Order;

use App\Event\Domain\Order\OrderPlacedEvent;
use App\Event\Domain\Order\OrderRefundedEvent;
use App\ServiceInterface\Analytics\Order\MetricsSubscriberInterface;
use App\ServiceInterface\Analytics\Order\OrderMetricsSubscriberInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final readonly class MetricsSubscriber implements MetricsSubscriberInterface, OrderMetricsSubscriberInterface
{
    public function __construct(private MetricsProjectionService $proj)
    {
    }

    #[AsEventListener(event: OrderPlacedEvent::class)]
    public function onPlaced(OrderPlacedEvent $e): void
    {
        $this->proj->projectOrderPlaced((string) $e->orderId, '0.00', null, new \DateTimeImmutable('now'));
    }

    #[AsEventListener(event: OrderRefundedEvent::class)]
    public function onRefunded(OrderRefundedEvent $e): void
    {
        $this->proj->projectRefund($e->amount, new \DateTimeImmutable('now'));
    }
}
