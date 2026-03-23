<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderPriceDetailInterface
{
    public function __construct(string $id, Currency $currency, int $subtotalMinor, int $discountMinor, int $taxMinor, int $totalMinor);

    public function id(): string;

    public function currency(): Currency;

    public function subtotalMinor(): int;

    public function discountMinor(): int;

    public function taxMinor(): int;

    public function totalMinor(): int;
}
