<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

final class OrderDemoService implements App\DemoInterface\Order\OrderDemoServiceInterface
{
    public function seed(): int
    {
        return 3;
    }
}
