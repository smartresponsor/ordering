<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderReturnRequestInterface
{
    public function __construct(string $id, string $orderId, int $amountMinor, string $currency, ?string $reason = null);

    public function id(): string;

    public function orderId(): string;

    public function amountMinor(): int;

    public function currency(): string;

    public function status(): string;

    public function approve(): void;

    public function markRefunded(): void;
}
