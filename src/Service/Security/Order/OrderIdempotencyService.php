<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\ServiceInterface\Security\Order\OrderIdempotencyServiceInterface;

final class OrderIdempotencyService implements OrderIdempotencyServiceInterface
{
    public function key(string $id): string
    {
        return $id;
    }
}
