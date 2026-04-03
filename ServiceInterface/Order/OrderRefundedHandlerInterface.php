<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Event\Domain\Order\OrderRefundedEvent;
use App\Repository\Order\OrderRepository;
use App\Service\Order\OrderStatusService;

interface OrderRefundedHandlerInterface
{
    public function __construct(
        OrderRepository $orders,
        OrderStatusService $status,
    );

    public function __invoke(OrderRefundedEvent $event): void;
}
