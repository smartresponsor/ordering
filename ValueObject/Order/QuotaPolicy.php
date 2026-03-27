<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

final class QuotaPolicy
{
    public function allow(HealthProbe $probe): bool
    {
        return $probe->quotaRemaining() > 0;
    }
}
