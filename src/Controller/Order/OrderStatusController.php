<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

final class OrderStatusController implements App\ControllerInterface\Order\OrderStatusControllerInterface
{
    public function status(): array
    {
        return ['ok' => true, 'phase' => 72];
    }
}
