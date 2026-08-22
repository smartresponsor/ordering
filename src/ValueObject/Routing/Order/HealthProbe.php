<?php

declare(strict_types=1);

namespace App\Ordering\ValueObject\Routing\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

final readonly class HealthProbe
{
    private int $p95Ms;
    private float $errorRate;
    private float $costPerTxn;
    private int $quotaRemaining;

    public function __construct(int $p95Ms, float $errorRate, float $costPerTxn, int $quotaRemaining)
    {
        $this->p95Ms = $p95Ms;
        $this->errorRate = $errorRate;
        $this->costPerTxn = $costPerTxn;
        $this->quotaRemaining = $quotaRemaining;
    }

    public function p95Ms(): int
    {
        return $this->p95Ms;
    }

    public function errorRate(): float
    {
        return $this->errorRate;
    }

    public function costPerTxn(): float
    {
        return $this->costPerTxn;
    }

    public function quotaRemaining(): int
    {
        return $this->quotaRemaining;
    }
}
