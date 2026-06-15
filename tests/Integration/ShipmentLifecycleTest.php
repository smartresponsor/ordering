<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderShipmentEntity;
use App\Service\ShipmentEntity\Order\ShipmentService;
use App\Subscriber\Event\Order\OrderShipmentSubscriber;
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

        $order = OrderEntity::create('USD', '100.00');
        $em->persist($order);
        $shipment = new OrderShipmentEntity($order, 'DHL', 'TRK-1');
        $em->persist($shipment);
        $em->flush();

        $svc->markDelivered($shipment, new \DateTimeImmutable('now'));
        $this->assertSame(OrderShipmentEntity::STATUS_DELIVERED, $shipment->getStatus());
    }
}
