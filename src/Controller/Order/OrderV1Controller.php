<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

final class OrderV1Controller implements App\ControllerInterface\Order\OrderV1ControllerInterface
{
    public function status(): array
    {
        return ['v' => 1, 'status' => 'green'];
    }
}
