<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderItemInterface
{
    public function __construct(Order $order, string $basePrice, string $currency);

    public function getOrder(): Order;

    public function getBasePrice(): string;

    public function getCurrency(): string;

    public function setFinalPrice(string $price): void;

    public function getFinalPrice(): string;

    public function setTaxRate(float $r): void;

    public function setDiscountPercent(float $p): void;
}
