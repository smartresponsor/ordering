<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

final class ExchangeRate
{
    public function __construct(
        public readonly string $baseCurrency,
        public readonly string $quoteCurrency,
        public readonly float $rate,
    ) {
    }
}
