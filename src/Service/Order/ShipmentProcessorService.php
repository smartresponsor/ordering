<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\Order\OrderShipment;
use App\ServiceInterface\Order\ShipmentProcessorServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ShipmentProcessorService implements ShipmentProcessorServiceInterface
{
    public function __construct(private readonly CarrierInterface $carrier, private readonly EntityManagerInterface $em)
    {
    }

    public function ship(Order $order, string $carrierName = 'UPS'): OrderShipment
    {
        $tracking = $this->carrier->createShipment($carrierName, $order->getId() ?? 0);
        $shipment = new OrderShipment($order, $carrierName, $tracking);
        $shipment->markShipped($tracking);
        $this->em->persist($shipment);

        return $shipment;
    }
}
