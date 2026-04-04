<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

final class OrderTaxationService implements \App\ServiceInterface\Pricing\Order\OrderTaxationServiceInterface
{
    public function apply(float $amount, float $rate): float
    {
        return $amount * (1 + $rate);
    }
}
