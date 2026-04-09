<?php

declare(strict_types=1);

namespace App\Message\Command;

final readonly class OrderCreateCommand
{
    public function __construct(
        public readonly string $currency,
        public readonly string $grandTotal,
    ) {
    }
}
