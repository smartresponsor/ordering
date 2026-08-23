<?php

declare(strict_types=1);

namespace App\Ordering\Message\Outbox;

final readonly class OrderOutboxDispatchedMessage
{
    public function __construct(
        public string $topic,
        public array $payload,
    ) {
    }
}
