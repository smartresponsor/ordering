<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

final class OrderPricingService implements \App\ServiceInterface\Pricing\Order\OrderPricingInterface
{
    public function price(float $base, float $rate): float
    {
        return round($base * (1.0 + max(0.0, $rate)), 2);
    }

    public function calculate(string $orderId): void
    {
        if ('' === trim($orderId)) {
            return;
        }

        // Compatibility hook for handler paths that only carry an order id.
        // The concrete pricing contract in this slice is float-based, so this
        // method intentionally stays side-effect free for now.
    }
}
