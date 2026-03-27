<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

final class OrderRefundService implements \App\ServiceInterface\Order\OrderRefundServiceInterface
{
    public function refund(float $amount): bool
    {
        return $amount >= 0;
    }
}
