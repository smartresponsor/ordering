<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

final class OrderArchivalService implements App\AuditInterface\Archival\Order\OrderArchivalInterface
{
    public function archive(string $aggregateId): bool
    {
        return '' !== $aggregateId;
    }
}
