<?php

declare(strict_types=1);

namespace App\Message\Command;

final readonly class OrderPayCommand
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $amount,
        public readonly string $externalRef,
    ) {
    }
}
