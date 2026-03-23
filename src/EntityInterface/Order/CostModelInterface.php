<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko / Marketing America Corp <dev@smartresponsor.com>.
 */

namespace App\EntityInterface\Order;

interface CostModelInterface
{
    public function __construct(array $costCentsPerProvider, int $maxCostCentsPerTxn = 50);

    public function estimate(string $provider, int $amountCents): int;

    public function allowed(string $provider, int $amountCents): bool;
}
