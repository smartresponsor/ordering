<?php

declare(strict_types=1);

namespace App\Service\Analytics\Order;

use App\Event\Domain\Order\OrderCancelledEvent;
use App\Event\Domain\Order\OrderPaidEvent;
use App\Event\Domain\Order\OrderPartiallyPaidEvent;
use App\Event\Domain\Order\OrderPlacedEvent;
use App\Event\Domain\Order\OrderRefundedEvent;
use App\Event\Domain\Order\OrderShippedEvent;
use App\ServiceInterface\Analytics\Order\OrderMetricsProjectorInterface;

final readonly class OrderMetricsProjector implements OrderMetricsProjectorInterface
{
    public function __construct(private OrderMetricsViewRepository $repo)
    {
    }

    public function rebuildForDay(\DateTimeImmutable $day): int
    {
        return 0;
    }

    public function __invoke(object $event): void
    {
        switch (true) {
            case $event instanceof OrderPlacedEvent:
                $vendorId = $event->vendorId ?? 'UNKNOWN';
                $amount = (string) ($event->amount ?? '0.00');
                $this->repo->upsert($vendorId, function ($v) use ($amount): void {
                    ++$v->totalOrders;
                    $v->totalRevenue = bcadd($v->totalRevenue, $amount, 2);
                    $v->avgOrderValue = $v->totalOrders > 0 ? bcdiv($v->totalRevenue, (string) $v->totalOrders, 2) : '0.00';
                });
                break;

            case $event instanceof OrderRefundedEvent:
                $vendorId = $event->vendorId ?? 'UNKNOWN';
                $amount = $event->amount ?? '0.00';
                $this->repo->upsert($vendorId, function ($v) use ($amount): void {
                    $v->refundedAmount = bcadd($v->refundedAmount, $amount, 2);
                });
                break;

            case $event instanceof OrderCancelledEvent:
                $vendorId = $event->vendorId ?? 'UNKNOWN';
                $this->repo->upsert($vendorId, function ($v): void {
                    ++$v->cancelledOrders;
                });
                break;

            case $event instanceof OrderPaidEvent:
            case $event instanceof OrderShippedEvent:
            case $event instanceof OrderPartiallyPaidEvent:
                break;
        }
    }
}
