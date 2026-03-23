<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ControllerInterface\Order;

interface OrderTransitionsControllerInterface
{
    public function __construct(
        OrderRepository $orders,
        OrderStatusService $status,
    );

    public function pay(string $id): JsonResponse;

    public function cancel(string $id): JsonResponse;

    public function refund(string $id, Request $request): JsonResponse;
}
