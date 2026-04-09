<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Workflow\Order;

use App\Entity\Order;
use App\Event\Domain\Order\OrderCancelledEvent;
use App\ServiceInterface\Workflow\Order\OrderCancelledHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderCancelledHandler implements OrderCancelledHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly OrderStatusService $status,
    ) {
    }

    public function __invoke(OrderCancelledEvent $event): void
    {
        if (!$event->order instanceof Order) {
            return;
        }

        $this->status->applyTransition($event->order, 'cancel');
    }
}
