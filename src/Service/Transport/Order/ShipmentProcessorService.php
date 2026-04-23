<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Transport\Order;

use App\Entity\Order;
use App\Entity\Order\OrderShipment;
use App\ServiceInterface\Transport\Order\CarrierInterface;
use App\ServiceInterface\Transport\Order\ShipmentProcessorServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ShipmentProcessorService implements ShipmentProcessorServiceInterface
{
    public function __construct(private CarrierInterface $carrier, private EntityManagerInterface $em)
    {
    }

    public function ship(Order $order, string $carrierName = 'UPS'): OrderShipment
    {
        $tracking = $this->carrier->ship($order->getId(), $carrierName);
        $shipment = new OrderShipment($order, $carrierName, $tracking);
        $shipment->markShipped($tracking);
        $this->em->persist($shipment);

        return $shipment;
    }
}
