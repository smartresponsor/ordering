<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

final readonly class OrderCompletedEvent
{
    public function __construct(
        public string $orderId,
        public ?string $vendorId = null,
    ) {
    }
}
