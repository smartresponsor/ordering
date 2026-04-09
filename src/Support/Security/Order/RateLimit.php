<?php

declare(strict_types=1);

namespace App\Support\Security\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class RateLimit
{
    private int $capacity;
    private float $refillPerSecond;

    /** @var array<string, array{token: float, time: int}> */
    private array $bucket = [];

    public function __construct(int $capacity = 10, float $refillPerSecond = 5.0)
    {
        $this->capacity = $capacity;
        $this->refillPerSecond = $refillPerSecond;
    }

    public function allow(string $key): bool
    {
        $now = time();
        $bucket = $this->bucket[$key] ?? ['token' => (float) $this->capacity, 'time' => $now];
        $elapsed = max(0, $now - $bucket['time']);
        $bucket['token'] = min((float) $this->capacity, $bucket['token'] + $elapsed * $this->refillPerSecond);
        $bucket['time'] = $now;

        if ($bucket['token'] < 1.0) {
            $this->bucket[$key] = $bucket;

            return false;
        }

        $bucket['token'] -= 1.0;
        $this->bucket[$key] = $bucket;

        return true;
    }
}
