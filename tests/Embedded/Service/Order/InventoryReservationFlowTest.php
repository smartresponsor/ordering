<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Integration\Inventory\InMemoryInventoryGateway;
use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderItemEntity;
use App\Service\Inventory\Order\InventoryService;
use App\Subscriber\Event\Order\InventorySubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class InventoryReservationFlowTest extends KernelTestCase
{
    public function testReserveReleaseConsume(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new InventorySubscriber());
        $gateway = new InMemoryInventoryGateway(['SKU-1' => 10, 'SKU-2' => 5]);
        $svc = new InventoryService($em, $dispatcher, $gateway);

        $order = OrderEntity::create('USD', '100.00');
        $em->persist($order);
        $item = new OrderItemEntity($order, '10.00', 'USD');
        $em->persist($item);
        $em->flush();

        $res = $svc->reserve($order, ['SKU-1' => 2, 'SKU-2' => 1], 'rkey-1');
        $this->assertSame('reserved', $res->getState());

        $svc->release($res);
        $this->assertSame('released', $res->getState());

        // reserve again and consume
        $res2 = $svc->reserve($order, ['SKU-1' => 3], 'rkey-2');
        $svc->consume($res2);
        $this->assertSame('consumed', $res2->getState());
    }

    public function testNotEnoughStockFails(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new InventorySubscriber());
        $gateway = new InMemoryInventoryGateway(['SKU-1' => 1]);
        $svc = new InventoryService($em, $dispatcher, $gateway);

        $order = OrderEntity::create('USD', '50.00');
        $em->persist($order);
        $em->flush();

        $this->expectException(\DomainException::class);
        $svc->reserve($order, ['SKU-1' => 2], 'rkey-3');
    }
}
