<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order\Order as OrderAggregate;
use App\Event\Domain\Order\OrderRefundedEvent;
use App\Repository\Order\OrderRepository;
use App\ServiceInterface\Order\OrderRefundedHandlerInterface;

final class OrderRefundedHandler implements OrderRefundedHandlerInterface
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly OrderStatusService $status,
    ) {
    }

    public function __invoke(OrderRefundedEvent $event): void
    {
        $order = $this->orders->findById($event->orderId);
        if (!$order) {
            // ленивое создание, если агрегата нет (можно заменить на exception)
            $order = new OrderAggregate($event->orderId);
        }
        $this->status->applyTransition($order, 'refund');
    }
}
