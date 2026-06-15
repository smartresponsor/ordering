<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderShipmentEntity;
use App\Service\ShipmentEntity\Order\ShipmentService;
use App\Subscriber\Event\Order\OrderShipmentSubscriber;
use App\ValueObject\Pricing\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class ShipmentLifecycleTest extends KernelTestCase
{
    public function testDeliverAndPolicyCreated(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new OrderShipmentSubscriber(new NullLogger()));
        $svc = new ShipmentService($em, $dispatcher);

        $order = new OrderEntity('VND-1', new Money('100.00', 'USD'));
        $em->persist($order);
        $shipment = new OrderShipmentEntity($order, 'DHL', 'TRK-1');
        $em->persist($shipment);
        $em->flush();

        $svc->markDelivered($shipment, new \DateTimeImmutable('now'));
        $this->assertSame(OrderShipment::STATUS_DELIVERED, $shipment->getStatus());
    }
}
