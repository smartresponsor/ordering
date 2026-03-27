<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\Order as OrderAggregate;
use App\Event\Order\OrderShippedEvent;
use App\Repository\Order\OrderRepository;
use App\ServiceInterface\Order\OrderShippedHandlerInterface;

final class OrderShippedHandler implements OrderShippedHandlerInterface
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly OrderStatusService $status,
    ) {
    }

    public function __invoke(OrderShippedEvent $event): void
    {
        $order = $this->orders->findById($event->orderId);
        if (!$order) {
            // ленивое создание, если агрегата нет (можно заменить на exception)
            $order = new OrderAggregate($event->orderId);
        }
        $this->status->applyTransition($order, 'ship');
    }
}
