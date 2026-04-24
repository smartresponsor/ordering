<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Shipment\Order;

use App\Entity\OrderShipmentView;
use App\RepositoryInterface\Order\OrderShipmentViewRepositoryInterface;
use App\ServiceInterface\Shipment\Order\OrderShipmentProjectionServiceInterface;

final readonly class OrderShipmentProjectionService implements OrderShipmentProjectionServiceInterface
{
    public function __construct(private OrderShipmentViewRepositoryInterface $repo)
    {
    }

    public function updateFromExternal(
        string $orderId,
        string $carrier,
        string $tracking,
        string $status,
        ?\DateTimeImmutable $deliveredAt = null,
    ): void {
        $normalizedStatus = strtolower($status);
        $view = $this->repo->find($orderId);

        if (null === $view) {
            $view = new OrderShipmentView($orderId, $carrier, $tracking, $normalizedStatus, $deliveredAt);
        } else {
            $view->update($carrier, $tracking, $normalizedStatus, $deliveredAt);
        }

        $this->repo->save($view);
    }
}
