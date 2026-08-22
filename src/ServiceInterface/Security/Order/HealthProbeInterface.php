<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Ordering\ServiceInterface\Security\Order;

interface HealthProbeInterface
{
    public function p95Ms(): int;

    public function errorRate(): float;

    public function costPerTxn(): float;

    public function quotaRemaining(): int;
}
