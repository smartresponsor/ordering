<?php

declare(strict_types=1);

namespace App\Service\Order;

final readonly class OutboxDispatchedMessage
{
    public function __construct(
        public string $topic,
        public array $payload,
    ) {
    }
}
