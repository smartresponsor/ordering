<?php

declare(strict_types=1);

namespace App\ServiceInterface\Pricing\Order;

use App\ValueObject\Pricing\Order\ExchangeRate;

interface ExchangeRateProviderInterface
{
    public function getRate(string $baseCurrency, string $quoteCurrency): ExchangeRate;
}
