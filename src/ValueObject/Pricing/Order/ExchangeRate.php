<?php

declare(strict_types=1);

namespace App\ValueObject\Pricing\Order;

final readonly class ExchangeRate
{
    public function __construct(
        private readonly string $baseCurrency,
        private readonly string $quoteCurrency,
        private readonly float $rate,
    ) {
        if ($this->rate <= 0) {
            throw new \InvalidArgumentException('Exchange rate must be positive.');
        }
    }

    public function baseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function quoteCurrency(): string
    {
        return $this->quoteCurrency;
    }

    public function rate(): float
    {
        return $this->rate;
    }
}
