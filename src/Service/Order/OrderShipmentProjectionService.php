<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\OrderShipmentView;
use App\Repository\Order\OrderShipmentViewRepository;
use App\ServiceInterface\Order\OrderShipmentProjectionServiceInterface;

final class OrderShipmentProjectionService implements OrderShipmentProjectionServiceInterface
{
    public function __construct(private OrderShipmentViewRepository $repo)
    {
    }

    public function updateFromExternal(
        string $orderId,
        string $carrier,
        string $tracking,
        string $status,
        ?\DateTimeImmutable $deliveredAt = null,
    ): void {
        $view = $this->repo->find($orderId) ?? new OrderShipmentView($orderId, $carrier, $tracking, $status);
        $view->update($carrier, $tracking, $status, $deliveredAt);
        $this->repo->save($view);
    }
}
