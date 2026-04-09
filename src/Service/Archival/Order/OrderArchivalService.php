<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Archival\Order;

use App\AuditInterface\Archival\Order\OrderArchivalInterface;

final class OrderArchivalService implements OrderArchivalInterface
{
    public function archive(string $aggregateId): bool
    {
        return '' !== $aggregateId;
    }
}
