<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

final class ProviderPolicy
{
    private float $weightLatency;
    private float $weightError;
    private float $weightCost;
    private int $thresholdP95;
    private float $thresholdError;

    public function __construct(
        float $weightLatency,
        float $weightError,
        float $weightCost,
        int $thresholdP95,
        float $thresholdError,
    ) {
        $this->weightLatency = $weightLatency;
        $this->weightError = $weightError;
        $this->weightCost = $weightCost;
        $this->thresholdP95 = $thresholdP95;
        $this->thresholdError = $thresholdError;
    }

    public function weightLatency(): float
    {
        return $this->weightLatency;
    }

    public function weightError(): float
    {
        return $this->weightError;
    }

    public function weightCost(): float
    {
        return $this->weightCost;
    }

    public function thresholdP95(): int
    {
        return $this->thresholdP95;
    }

    public function thresholdError(): float
    {
        return $this->thresholdError;
    }
}
