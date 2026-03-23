<?php

declare(strict_types=1);

namespace App\Service\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

final class CanarySwitch
{
    private int $seed;

    public function __construct(int $seed = 42)
    {
        $this->seed = $seed;
    }

    public function allow(string $intentId, float $percent): bool
    {
        $h = crc32($intentId.':'.(string) $this->seed);
        $mod = $h % 10000; // 0..9999
        $threshold = (int) round($percent * 100.0);

        return $mod < $threshold;
    }
}
