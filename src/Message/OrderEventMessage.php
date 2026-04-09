<?php

declare(strict_types=1);

namespace App\Message;

final readonly class OrderEventMessage
{
    public function __construct(
        public readonly string $eventName,
        public readonly string $orderId,
    ) {
    }
}
