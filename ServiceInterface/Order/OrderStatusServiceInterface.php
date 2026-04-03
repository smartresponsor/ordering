<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

interface OrderStatusServiceInterface
{
    public function __construct(
        WorkflowInterface $orderWorkflow,
        EntityManagerInterface $em,
    );

    public function canTransition(Order $order, string $transition): bool;

    public function applyTransition(Order $order, string $transition): void;
}
