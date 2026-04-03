<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Event\Domain\Order\OrderCancelledEvent;
use App\Event\Domain\Order\OrderPlacedEvent;
use App\Event\Domain\Order\OrderRefundedEvent;
use App\ServiceInterface\Order\OrderMetricsRollupAggregatorInterface;

final class OrderMetricsRollupAggregator implements OrderMetricsRollupAggregatorInterface
{
    public function __construct(private readonly OrderMetricsRollupViewRepository $repo)
    {
    }

    private function periods(\DateTimeImmutable $at): array
    {
        $q = 'Q'.(int) ceil(((int) $at->format('n')) / 3);
        $quarter = $at->format('Y-').$q;
        $year = $at->format('Y');

        return [
            ['quarter', $quarter],
            ['year', $year],
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
