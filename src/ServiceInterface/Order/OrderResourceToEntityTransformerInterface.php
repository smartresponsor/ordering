<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

use App\ApiResource\Order\OrderResource;
use App\Entity\Order\Order as OrderEntity;

interface OrderResourceToEntityTransformerInterface
{
    public function transform(OrderResource $r): OrderEntity;
}
