<?php

declare(strict_types=1);

namespace App\Service\Order;

final class OrderDomainMessage
{
    public function __construct(
        public string $messageId,
        public string $topic,
        public array $payload,
    ) {
    }
}
