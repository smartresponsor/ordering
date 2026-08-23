<?php

declare(strict_types=1);

namespace App\Ordering\Message;

final readonly class OrderEventMessage
{
    public function __construct(
        public string $eventName,
        public string $orderId,
    ) {
    }
}
