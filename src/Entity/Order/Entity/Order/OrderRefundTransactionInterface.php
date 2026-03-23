<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Entity\Order\Entity\Order;

interface OrderRefundTransactionInterface
{
    public function id(): string;

    public function orderId(): string;

    public function amountMinor(): int;

    public function currency(): string;

    public function status(): string;

    public function paymentId(): string;

    public function markCompleted(): void;
}
