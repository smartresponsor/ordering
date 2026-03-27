<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Event\Order\OrderCancelledEvent;
use App\Event\Order\OrderPaidEvent;
use App\Event\Order\OrderPartiallyPaidEvent;
use App\Event\Order\OrderPlacedEvent;
use App\Event\Order\OrderRefundedEvent;
use App\Event\Order\OrderShippedEvent;
use App\ServiceInterface\Order\OrderMetricsProjectorInterface;

final class OrderMetricsProjector implements OrderMetricsProjectorInterface
{
    public function __construct(private readonly OrderMetricsViewRepository $repo)
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
                $amount = (string) ($event->amount ?? '0.00');
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
