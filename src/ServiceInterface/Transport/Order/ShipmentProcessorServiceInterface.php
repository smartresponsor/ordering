<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Transport\Order;

use App\Entity\Order;
use App\Entity\Order\OrderShipment;
use App\ServiceInterface\Transport\Order\CarrierInterface;
use Doctrine\ORM\EntityManagerInterface;

interface ShipmentProcessorServiceInterface
{
    public function __construct(CarrierInterface $carrier, EntityManagerInterface $em);

    public function ship(Order $order, string $carrierName = 'UPS'): OrderShipment;
}
