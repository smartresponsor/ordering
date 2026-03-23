<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

use App\Contract\Order\TaxBreakdown;
use App\Entity\Order\Order;

interface EUTaxStrategyInterface
{
    public function calculate(Order $order, ?string $countryCode = null): TaxBreakdown;
}
