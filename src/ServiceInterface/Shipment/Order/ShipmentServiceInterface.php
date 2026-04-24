<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Shipment\Order;

use App\Entity\Order\OrderReturnPolicy;
use App\Entity\OrderShipment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

interface ShipmentServiceInterface
{
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $events,
    );

    public function markShipped(string $orderId, string $tracking, ?string $carrier = null): void;

    public function markDelivered(OrderShipment $shipment, \DateTimeInterface $at): void;

    public function completeShipment(OrderShipment $shipment): void;

    public function expireIfNeeded(OrderReturnPolicy $policy, \DateTimeInterface $now): bool;
}
