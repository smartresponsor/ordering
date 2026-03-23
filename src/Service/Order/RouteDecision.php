<?php

declare(strict_types=1);

namespace App\Service\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

final class RouteDecision
{
    private string $provider;
    private float $score;

    public function __construct(string $provider, float $score)
    {
        $this->provider = $provider;
        $this->score = $score;
    }

    public function provider(): string
    {
        return $this->provider;
    }

    public function score(): float
    {
        return $this->score;
    }
}
