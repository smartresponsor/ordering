<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Event\Order\OrderCancelledEvent;
use App\Event\Order\OrderPlacedEvent;
use App\Event\Order\OrderRefundedEvent;
use App\ServiceInterface\Order\OrderMetricsAggregatorInterface;

final class OrderMetricsAggregator implements OrderMetricsAggregatorInterface
{
    public function __construct(private readonly OrderMetricsAggregateViewRepository $repo)
    {
    }

    private function periods(\DateTimeImmutable $at): array
    {
        $day = $at->format('Y-m-d');
        $week = $at->format('o-\WW');
        $month = $at->format('Y-m');

        return [
            ['day', $day],
            ['week', $week],
            ['month', $month],
        ];
    }

    public function __invoke(object $event): void
    {
        $vendorId = $event->vendorId ?? 'UNKNOWN';
        $amount = (string) ($event->amount ?? '0.00');
        $at = new \DateTimeImmutable('now');

        foreach ($this->periods($at) as [$type, $value]) {
            if ($event instanceof OrderPlacedEvent) {
                $this->repo->upsert($vendorId, $type, $value, function ($v) use ($amount): void {
                    ++$v->totalOrders;
                    $v->totalRevenue = bcadd($v->totalRevenue, $amount, 2);
                });
            } elseif ($event instanceof OrderRefundedEvent) {
                $this->repo->upsert($vendorId, $type, $value, function ($v) use ($amount): void {
                    $v->refundedAmount = bcadd($v->refundedAmount, $amount, 2);
                });
            } elseif ($event instanceof OrderCancelledEvent) {
                $this->repo->upsert($vendorId, $type, $value, function ($v): void {
                });
            }
        }
    }
}
