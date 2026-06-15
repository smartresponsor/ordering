<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\Entity\Order\OrderItemEntity;
use App\ServiceInterface\Pricing\Order\FlatPromotionStrategyInterface;
use App\ServiceInterface\Pricing\Order\PromotionStrategyInterface;

readonly class FlatPromotionStrategy implements PromotionStrategyInterface, FlatPromotionStrategyInterface
{
    public function __construct(private int $percent = 10)
    {
    }

    public function discountFor(OrderItemEntity $OrderItemEntity): int
    {
        $base = $OrderItemEntity->getUnitPrice() * $OrderItemEntity->getQuantity();

        return (int) round($base * ($this->percent / 100));
    }
}
