<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderRefundLedgerInterface
{
    public function __construct(Order $order, string $key, string $amount, string $currency);

    public function getAmount(): string;

    public function getCurrency(): string;

    public function getKey(): string;
}
