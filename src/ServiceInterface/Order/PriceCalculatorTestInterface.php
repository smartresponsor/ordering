<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

interface PriceCalculatorTestInterface
{
    public function test_calculate_with_discount_and_tax(): void;

    public function test_calculate_with_currency_conversion(): void;
}
