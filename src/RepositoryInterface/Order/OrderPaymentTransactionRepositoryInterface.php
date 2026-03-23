<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\RepositoryInterface\Order;

interface OrderPaymentTransactionRepositoryInterface
{
    public function add(OrderPaymentTransaction $tx): void;

    public function findByOrder(string $orderId): iterable;

    public function sumSucceededByOrder(string $orderId): string;
}
