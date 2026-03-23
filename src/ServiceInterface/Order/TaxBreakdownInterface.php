<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order\Order;
use App\Service\Order\TaxBreakdown;

interface TaxBreakdownInterface
{
    public function calculate(Order $order, ?string $countryCode = null): TaxBreakdown;
}
