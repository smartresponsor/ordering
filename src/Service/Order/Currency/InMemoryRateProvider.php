<?php

declare(strict_types=1);

namespace App\Service\Order\Currency;

final class InMemoryRateProvider
{
    public function __construct(private readonly array $pairs = [])
    {
    }

    public function rate(string $from, string $to): float
    {
        return (float) ($this->pairs[$from.':'.$to] ?? 1.0);
    }
}
