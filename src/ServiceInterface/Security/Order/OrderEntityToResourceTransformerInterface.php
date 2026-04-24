<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Security\Order;

use App\ApiResource\View\Order\OrderResource;
use App\Entity\Order as OrderEntity;

interface OrderEntityToResourceTransformerInterface
{
    public function transform(OrderEntity $e): OrderResource;
}
