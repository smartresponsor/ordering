<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

interface OrderMetricsProjectionInterface
{
    public function addOrder(string $amount): void;

    public function addRefund(string $amount): void;

    public function getDate(): \DateTimeImmutable;

    public function getOrdersCount(): int;

    public function getGrossTotal(): string;

    public function getRefundTotal(): string;

    public function getNetTotal(): string;
}
