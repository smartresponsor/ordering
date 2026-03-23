<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

interface RouteContextInterface
{
    public function intentId(): string;

    public function region(): string;

    public function amountUsd(): float;

    public function canary(): bool;
}
