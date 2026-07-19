<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Workflow\Order;

use App\Event\Domain\Order\OrderPlacedEvent;
use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Repository\Order\OrderRepository;
use App\ServiceInterface\Workflow\Order\OrderPlacedHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderPlacedHandler implements OrderPlacedHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderStatusService $status,
    ) {
    }

    public function __invoke(OrderPlacedEvent $event): void
    {
        /** @var OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($event->orderId);
        if (!$order instanceof OrderEntity) {
            return;
        }

        $this->status->applyTransition($order, 'place');
    }
}
