<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use App\ValueObject\Order\ExchangeRate;

interface ExchangeRateProviderInterface
{
    public function getRate(string $baseCurrency, string $quoteCurrency): ExchangeRate;
}
