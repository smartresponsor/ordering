<?php

declare(strict_types=1);

namespace App\Service\Shipment;

use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderShipmentEntity;
use App\ServiceInterface\Shipment\CarrierInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderShipmentProcessorService
{
    public function __construct(
        private CarrierInterface $carrier,
        private EntityManagerInterface $em,
    ) {
    }

    public function ship(OrderEntity $order, string $carrierName = 'UPS'): OrderShipmentEntity
    {
        $tracking = $this->carrier->createShipment($carrierName, (string) ($order->getId() ?? 0));
        $shipment = $order->ship($carrierName, $tracking);
        $this->em->persist($shipment);

        return $shipment;
    }
}
