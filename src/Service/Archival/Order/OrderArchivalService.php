<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Archival\Order;

use App\Ordering\ServiceInterface\Archival\Order\OrderArchivalServiceInterface;

final class OrderArchivalService implements OrderArchivalServiceInterface
{
    public function archive(string $aggregateId): bool
    {
        return '' !== $aggregateId;
    }
}
