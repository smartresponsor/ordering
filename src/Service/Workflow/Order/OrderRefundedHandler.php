<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Workflow\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Event\Domain\Order\OrderRefundedEvent;
use App\Ordering\Repository\Order\OrderRepository;
use App\Ordering\ServiceInterface\Workflow\Order\OrderRefundedHandlerInterface;
use App\Ordering\ValueObject\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderRefundedHandler implements OrderRefundedHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderStatusService $status,
    ) {
    }

    public function __invoke(OrderRefundedEvent $event): void
    {
        /** @var OrderRepository $repository */
        $repository = $this->em->getRepository(OrderEntity::class);
        $order = $repository->findByIdentifier($event->orderId);
        if (!$order instanceof OrderEntity || OrderStatus::Refunded->value === $order->getStatus()) {
            return;
        }

        $this->status->applyTransition($order, 'refund');
    }
}
