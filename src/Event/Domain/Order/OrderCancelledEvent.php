<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

final readonly class OrderCancelledEvent
{
    public function __construct(
        public string $orderId,
        public ?string $vendorId = null,
    ) {
    }

    public function getName(): string
    {
        return self::class;
    }
}
