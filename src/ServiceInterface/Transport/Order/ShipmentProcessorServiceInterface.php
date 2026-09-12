<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Transport\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderShipmentEntity;
use Doctrine\ORM\EntityManagerInterface;

interface ShipmentProcessorServiceInterface
{
    public function __construct(CarrierInterface $carrier, EntityManagerInterface $em);

    public function ship(OrderEntity $orderEntity, string $carrierName = 'UPS'): OrderShipmentEntity;
}
