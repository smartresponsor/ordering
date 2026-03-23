<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

final class OrderController implements App\ControllerInterface\Order\OrderControllerInterface
{
    public function index(): array
    {
        return ['status' => 'ok'];
    }
}
