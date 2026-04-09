<?php

declare(strict_types=1);

namespace App\Message\Command;

final readonly class RetryOrderSagaCommand
{
    public function __construct(public readonly string $orderId, public readonly int $attempt = 1, public readonly array $context = []) {
    }
}
