<?php

declare(strict_types=1);

namespace App\Entity\Order;

final readonly class WebhookLog
{
    public function __construct(
        private string $key,
        private string $eventType,
        private string $payload,
    ) {
    }

    public function key(): string
    {
        return $this->key;
    }
}
