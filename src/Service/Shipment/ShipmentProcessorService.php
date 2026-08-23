<?php

declare(strict_types=1);

namespace App\Ordering\Service\Shipment;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderShipmentEntity;
use App\Ordering\ServiceInterface\Shipment\CarrierInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ShipmentProcessorService
{
    public function __construct(private CarrierInterface $carrier, private EntityManagerInterface $em)
    {
    }

    public function ship(OrderEntity $order, string $carrierName = 'UPS'): OrderShipmentEntity
    {
        $tracking = $this->carrier->createShipment($carrierName, $order->slug());
        $shipment = $order->ship($carrierName, $tracking);
        $shipment->markShipped();
        $this->em->persist($shipment);
        $this->em->persist($order);

        return $shipment;
    }
}
