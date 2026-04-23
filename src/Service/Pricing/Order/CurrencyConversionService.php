<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\ServiceInterface\Pricing\Order\CurrencyConversionServiceInterface;
use App\ServiceInterface\Pricing\Order\ExchangeRateProviderInterface;
use App\ValueObject\Pricing\Order\Currency;
use App\ValueObject\Pricing\Order\Money;
use Symfony\Component\Yaml\Yaml;

final readonly class CurrencyConversionService implements CurrencyConversionServiceInterface
{
    private ExchangeRateProviderInterface $provider;

    public function __construct(ExchangeRateProviderInterface|string $provider)
    {
        if (is_string($provider)) {
            $parsed = Yaml::parseFile($provider);
            $base = strtoupper((string) ($parsed['exchange_rates']['base'] ?? 'USD'));
            $rates = [];
            foreach (($parsed['exchange_rates']['rates'] ?? []) as $quote => $rate) {
                $rates[$base.':'.strtoupper((string) $quote)] = (float) $rate;
            }
            $provider = new InMemoryRateProvider($rates);
        }

        $this->provider = $provider;
    }

    public function convert(Money $money, Currency|string $toCurrency, int $scale = 2): Money
    {
        $from = $money->getCurrency()->getCode();
        $to = $toCurrency instanceof Currency ? $toCurrency->getCode() : strtoupper($toCurrency);
        if ($from === $to) {
            return $money->round($scale);
        }
        $rate = $this->provider->getRate($from, $to)->rate;
        $converted = bcmul($money->getAmount(), (string) $rate, max(6, $scale));

        return new Money($converted, $to)->round($scale);
    }
}
