<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Pricing\Order;

use App\Ordering\ServiceInterface\Pricing\Order\OrderPricingInterface;

final class OrderPricingService implements OrderPricingInterface
{
    public function price(float $base, float $rate): float
    {
        return round($base * (1.0 + max(0.0, $rate)), 2);
    }

    public function calculate(string $orderId): void
    {
        // Compatibility hook for handler paths that only carry an order id.
        // The concrete pricing contract in this slice is float-based, so this
        // method intentionally stays side-effect free for now.
    }
}
