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
            $config = is_array($parsed) ? $parsed : [];
            $exchangeRates = is_array($config['exchange_rates'] ?? null) ? $config['exchange_rates'] : [];
            $base = strtoupper((string) ($exchangeRates['base'] ?? 'USD'));
            $rates = [];
            $pairs = is_array($exchangeRates['rates'] ?? null) ? $exchangeRates['rates'] : [];
            foreach ($pairs as $quote => $rate) {
                if (!is_scalar($quote) || !is_numeric($rate)) {
                    continue;
                }
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
        $rate = $this->provider->getRate($from, $to)->rate();
        $converted = bcmul($money->getAmount(), (string) $rate, max(6, $scale));

        return (new Money($converted, $to))->round($scale);
    }
}
