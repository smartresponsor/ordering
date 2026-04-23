<?php

declare(strict_types=1);

namespace App\ServiceInterface\Archival\Order;

final readonly class OrderAuditTrail
{
    public function __construct(
        public string $orderId,
        public int $totalEvents,
        public array $events,
    ) {
    }
}
