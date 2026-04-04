<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class RetryStormGuard
{
    /** @var array<string,int> */
    private array $count;
    private int $limit;
    private int $windowSeconds;
    /** @var array<string,int> */
    private array $windowStart;

    public function __construct(int $limit = 5, int $windowSeconds = 60)
    {
        $this->count = [];
        $this->windowStart = [];
        $this->limit = $limit;
        $this->windowSeconds = $windowSeconds;
    }

    public function allow(string $key): bool
    {
        $now = time();
        $start = $this->windowStart[$key] ?? $now;
        if ($now - $start > $this->windowSeconds) {
            $this->windowStart[$key] = $now;
            $this->count[$key] = 0;
        }
        $this->count[$key] = ($this->count[$key] ?? 0) + 1;

        return $this->count[$key] <= $this->limit;
    }
}
