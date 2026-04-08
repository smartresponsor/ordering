<?php

declare(strict_types=1);

namespace App\Message;

final readonly class OrderMessage
{
    public function __construct(
        public string $eventName,
        public string $orderId,
    ) {
    }
}
