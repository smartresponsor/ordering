<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Archival\Order;

use App\RepositoryInterface\Order\OrderEventRepositoryInterface;

interface OrderAuditTrailBuilderInterface
{
    public function __construct(OrderEventRepositoryInterface $repo);

    public function buildForOrder(string $orderId): OrderAuditTrail;
}
