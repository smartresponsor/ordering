<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

final class OrderAuditTrail
{
    public function __construct(
        public string $orderId,
        public int $totalEvents,
        public array $events,
    ) {
    }
}

interface OrderAuditTrailBuilderInterface
{
    public function buildForOrder(string $orderId): OrderAuditTrail;
}
