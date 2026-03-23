<?php

declare(strict_types=1);

namespace App\Service\Order\Currency;

final class CurrencyConversionService
{
    public function __construct(private readonly ?InMemoryRateProvider $provider = null)
    {
    }

    public function convert(string $amount, string $from, string $to): string
    {
        $rate = $this->provider?->rate($from, $to) ?? 1.0;

        return number_format(((float) $amount) * $rate, 2, '.', '');
    }
}
