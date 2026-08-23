<?php

declare(strict_types=1);

namespace App\Ordering\Message\Domain\Order;

final readonly class OrderDomainMessage
{
    public function __construct(
        public string $messageId,
        public string $topic,
        public array $payload,
    ) {
    }
}
