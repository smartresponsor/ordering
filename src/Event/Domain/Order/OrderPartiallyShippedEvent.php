<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

final readonly class OrderPartiallyShippedEvent
{
    public function __construct(
        public readonly string $orderId,
        public readonly int $count,
        public readonly ?string $note = null,
    ) {
    }
}
