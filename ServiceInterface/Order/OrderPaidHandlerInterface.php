<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Event\Order\OrderPaidEvent;
use App\Repository\Order\OrderRepository;
use App\Service\Order\OrderStatusService;

interface OrderPaidHandlerInterface
{
    public function __construct(
        OrderRepository $orders,
        OrderStatusService $status,
    );

    public function __invoke(OrderPaidEvent $event): void;
}
