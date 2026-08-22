<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Pricing\Order;

use App\Ordering\ValueObject\Pricing\Order\ExchangeRate;

interface ExchangeRateProviderInterface
{
    public function getRate(string $baseCurrency, string $quoteCurrency): ExchangeRate;
}
