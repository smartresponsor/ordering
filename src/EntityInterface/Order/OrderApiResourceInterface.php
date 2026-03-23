<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderApiResourceInterface
{
    public function __construct(
        #[Groups(['order:read'])] ?string $id = null,
        #[Groups(['order:read', 'order:write'])] ?string $status = null,
        #[Groups(['order:read', 'order:write'])] ?string $currency = null,
        #[Groups(['order:read'])] ?string $total = null,
    );
}
