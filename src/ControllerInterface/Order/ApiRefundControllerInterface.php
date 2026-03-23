<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ControllerInterface\Order;

interface ApiRefundControllerInterface
{
    public function __construct(OrderRefundTransactionRepository $repo);

    public function __invoke(string $id): iterable;
}
