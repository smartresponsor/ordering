<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ValueObject\Pricing\Order;

final readonly class TaxRuleSet
{
    /** @param array<string,mixed> $rules */
    public function __construct(public readonly string $region, public readonly array $rules)
    {
    }
}
