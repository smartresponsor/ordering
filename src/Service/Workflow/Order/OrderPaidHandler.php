<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Workflow\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Event\Domain\Order\OrderPaidEvent;
use App\Ordering\Repository\Order\OrderRepository;
use App\Ordering\ServiceInterface\Workflow\Order\OrderPaidHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderPaidHandler implements OrderPaidHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderStatusService $status,
    ) {
    }

    public function __invoke(OrderPaidEvent $event): void
    {
        /** @var OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($event->orderId);
        if (!$order instanceof OrderEntity) {
            return;
        }

        $this->status->applyTransition($order, 'pay');
    }
}
