<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

final class OrderReservationService implements App\ServiceInterface\Order\OrderReservationServiceInterface
{
    public function reserve(string $sku, int $qty): bool
    {
        return $qty > 0;
    }
}
