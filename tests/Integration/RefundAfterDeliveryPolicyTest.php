<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderShipmentEntity;
use App\Service\Refund\Order\RefundEligibilityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class RefundAfterDeliveryPolicyTest extends KernelTestCase
{
    public function testRefundNotAllowedAfterWindow(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $elig = self::$kernel->getContainer()->get(RefundEligibilityService::class);

        $order = OrderEntity::create('USD', '100.00');
        $em->persist($order);
        $shipment = new OrderShipmentEntity($order, 'UPS', 'TRK-2');
        $em->persist($shipment);
        $em->flush();

        // simulate old delivery
        $shipment->markDelivered((new \DateTimeImmutable('now'))->modify('-30 days'));
        $em->flush();

        $this->assertFalse($elig->canRefund($order));
    }
}
