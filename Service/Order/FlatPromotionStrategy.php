<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\OrderItem;
use App\ServiceInterface\Order\FlatPromotionStrategyInterface;
use App\ServiceInterface\Order\PromotionStrategyInterface;

class FlatPromotionStrategy implements PromotionStrategyInterface, FlatPromotionStrategyInterface
{
    public function __construct(private readonly int $percent = 10)
    {
    }

    public function discountFor(OrderItem $orderItem): int
    {
        $base = $orderItem->getUnitPrice() * $orderItem->getQuantity();

        return (int) round($base * ($this->percent / 100));
    }
}
