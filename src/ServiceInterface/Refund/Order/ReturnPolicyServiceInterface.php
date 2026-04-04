<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Refund\Order;

interface ReturnPolicyServiceInterface
{
    public function __construct(int $refundWindowDays = 14);

    public function canReturn(?\DateTimeImmutable $deliveredAt, ?\DateTimeImmutable $now = null): bool;
}
