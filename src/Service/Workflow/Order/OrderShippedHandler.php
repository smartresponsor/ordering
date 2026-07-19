<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Workflow\Order;

use App\Event\Domain\Order\OrderShippedEvent;
use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Repository\Order\OrderRepository;
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
        /** @var OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($event->orderId);
        if (!$order instanceof OrderEntity) {
            return;
        }

        $this->status->applyTransition($order, 'ship');
    }
}
