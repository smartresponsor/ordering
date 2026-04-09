<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\ServiceInterface\Pricing\Order\ExchangeRateProviderInterface;
use App\ValueObject\Pricing\Order\ExchangeRate;

final readonly class InMemoryRateProvider implements ExchangeRateProviderInterface
{
    /** @param array<string,float> $pairs  Format: "USD:EUR" => 0.92 */
    public function __construct(private readonly array $pairs) {
    }

    public function getRate(string $baseCurrency, string $quoteCurrency): ExchangeRate
    {
        if ($baseCurrency === $quoteCurrency) {
            return new ExchangeRate($baseCurrency, $quoteCurrency, 1.0);
        }
        $key = strtoupper($baseCurrency).':'.strtoupper($quoteCurrency);
        if (!array_key_exists($key, $this->pairs)) {
            throw new \RuntimeException("Rate not found for $key");
        }

        return new ExchangeRate(strtoupper($baseCurrency), strtoupper($quoteCurrency), (float) $this->pairs[$key]);
    }
}
