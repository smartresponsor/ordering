<?php

declare(strict_types=1);

namespace App\Service\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

final class CostPolicy
{
    public function normalize(float $cost): float
    {
        // Normalize cost in USD into [0..1] by a simple sigmoid-like compression.
        $x = max(0.0, $cost);

        return 1.0 - (1.0 / (1.0 + $x));
    }
}
