<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Workflow\Order;

use App\Entity\Order;
use App\ServiceInterface\Workflow\Order\OrderStatusServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class OrderStatusService implements OrderStatusServiceInterface
{
    public function __construct(
        private WorkflowInterface $orderWorkflow,
        private EntityManagerInterface $em,
    ) {
    }

    public function canTransition(Order $order, string $transition): bool
    {
        return $this->orderWorkflow->can($order, $transition);
    }

    public function applyTransition(Order $order, string $transition): void
    {
        if (!$this->orderWorkflow->can($order, $transition)) {
            throw new \RuntimeException(sprintf('Invalid transition %s for order %s', $transition, $order->getId()));
        }
        $this->orderWorkflow->apply($order, $transition);
        $this->em->persist($order);
        $this->em->flush();
    }
}
