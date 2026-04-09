<?php

declare(strict_types=1);

namespace App\ServiceInterface\Archival\Order;

final readonly class OrderAuditTrail
{
    public function __construct(
        public readonly string $orderId,
        public readonly int $totalEvents,
        public readonly array $events,
    ) {
    }
}
