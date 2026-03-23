<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order\Order;
use App\Entity\Order\OrderShipment;
use App\Service\Order\RefundEligibilityService;
use App\ValueObject\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class RefundAfterDeliveryPolicyTest extends KernelTestCase
{
    public function testRefundNotAllowedAfterWindow(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $elig = self::$kernel->getContainer()->get(RefundEligibilityService::class);

        $order = new Order('VND-1', new Money('100.00', 'USD'));
        $em->persist($order);
        $shipment = new OrderShipment($order, 'UPS', 'TRK-2');
        $em->persist($shipment);
        $em->flush();

        // simulate old delivery
        $shipment->markDelivered((new \DateTimeImmutable('now'))->modify('-30 days'));
        $em->flush();

        $this->assertFalse($elig->canRefund($order));
    }
}
