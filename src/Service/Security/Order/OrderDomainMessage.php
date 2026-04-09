<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

final readonly class OrderDomainMessage
{
    public function __construct(
        public readonly string $messageId,
        public readonly string $topic,
        public readonly array $payload,
    ) {
    }
}
