<?php

declare(strict_types=1);

namespace App\Message\Domain\Order;

final readonly class OrderDomainMessage
{
    public function __construct(
        public readonly string $messageId,
        public readonly string $topic,
        public readonly array $payload,
    ) {
    }
}
