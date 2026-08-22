<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Security\Order;

use App\Ordering\DemoInterface\Scenario\Order\OrderDemoServiceInterface;

final class OrderDemoService implements OrderDemoServiceInterface
{
    public function seed(): int
    {
        return 3;
    }
}
