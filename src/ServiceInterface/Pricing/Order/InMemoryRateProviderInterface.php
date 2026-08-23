<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Pricing\Order;

interface InMemoryRateProviderInterface
{
    public function __construct(array $pairs);

    public function getRate(string $baseCurrency, string $quoteCurrency): ExchangeRate;
}
