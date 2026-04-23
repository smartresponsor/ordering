<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Workflow\Order;

use App\Entity\Order;
use App\Event\Domain\Order\OrderShippedEvent;
use App\ServiceInterface\Workflow\Order\OrderShippedHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderShippedHandler implements OrderShippedHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderStatusService $status,
    ) {
    }

    public function __invoke(OrderShippedEvent $event): void
    {
        $order = $this->em->getRepository(Order::class)->findOneBy(['id' => $event->orderId]);
        if (!$order instanceof Order) {
            return;
        }

        $this->status->applyTransition($order, 'ship');
    }
}
