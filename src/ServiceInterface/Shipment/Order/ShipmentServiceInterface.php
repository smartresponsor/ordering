<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Shipment\Order;

use App\Model\Order\OrderReturnPolicy;
use App\Ordering\Entity\Order\OrderShipmentEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

interface ShipmentServiceInterface
{
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $events,
    );

    public function markShipped(string $orderId, string $tracking, ?string $carrier = null): void;

    public function markDelivered(OrderShipmentEntity $shipment, \DateTimeInterface $at): void;

    public function completeShipment(OrderShipmentEntity $shipment): void;

    public function expireIfNeeded(OrderReturnPolicy $policy, \DateTimeInterface $now): bool;
}
