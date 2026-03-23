<?php

declare(strict_types=1);

namespace App\Entity\Order;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko / Marketing America Corp <dev@smartresponsor.com>.
 */
final class CostModel
{
    /** @param array<string,int> $costCentsPerProvider */
    public function __construct(private array $costCentsPerProvider, private int $maxCostCentsPerTxn = 50)
    {
    }

    public function estimate(string $provider, int $amountCents): int
    {
        return (int) ($this->costCentsPerProvider[$provider] ?? 0);
    }

    public function allowed(string $provider, int $amountCents): bool
    {
        return $this->estimate($provider, $amountCents) <= $this->maxCostCentsPerTxn;
    }
}
