<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Security\Order;

use App\Ordering\Entity\Order\OrderEntity;

interface OrderFactoryInterface
{
    public function create(float $total = 100.00): OrderEntity;
}
