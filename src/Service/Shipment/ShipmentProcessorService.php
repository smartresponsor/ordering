<?php

declare(strict_types=1);

namespace App\Service\Shipment;

use App\Entity\Order;
use App\Entity\OrderShipment;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ShipmentProcessorService
{
    public function __construct(private CarrierInterface $carrier, private EntityManagerInterface $em)
    {
    }

    public function ship(Order $order, string $carrierName = 'UPS'): OrderShipment
    {
        $tracking = $this->carrier->createShipment($carrierName, $order->getId() ?? 0);
        $s = new OrderShipment($order, $carrierName, $tracking);
        $s->markShipped($tracking);
        $this->em->persist($s);

        return $s;
    }
}
