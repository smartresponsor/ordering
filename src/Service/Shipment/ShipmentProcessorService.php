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
        $tracking = $this->carrier->createShipment($carrierName, (string) ($order->getId() ?? 0));
        $s = new OrderShipmentEntity($order, $carrierName, $tracking);
        $s->markShipped($tracking);
        $this->em->persist($s);

        return $s;
    }
}
