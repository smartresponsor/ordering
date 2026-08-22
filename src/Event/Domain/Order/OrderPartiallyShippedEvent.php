<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

final readonly class OrderPartiallyShippedEvent
{
    public function __construct(
        public string $orderId,
        public int $count,
        public ?string $note = null,
    ) {
    }
}
