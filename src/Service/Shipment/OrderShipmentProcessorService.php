<?php

declare(strict_types=1);

namespace App\Service\Shipment;

use App\Entity\Order;
use App\Entity\OrderShipment;
use App\ServiceInterface\Shipment\CarrierInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderShipmentProcessorService
{
    public function __construct(
        private CarrierInterface $carrier,
        private EntityManagerInterface $em,
    ) {
    }

    public function ship(Order $order, string $carrierName = 'UPS'): OrderShipment
    {
        $tracking = $this->carrier->createShipment($carrierName, $order->getId());
        $shipment = $order->ship($carrierName, $tracking);
        $this->em->persist($shipment);

        return $shipment;
    }
}
