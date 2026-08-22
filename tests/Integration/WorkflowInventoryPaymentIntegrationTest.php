<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderItemEntity;
use App\Ordering\Service\Workflow\Order\OrderWorkflowService;
use App\Ordering\ValueObject\OrderStatus;
use App\Ordering\ValueObject\Pricing\Order\Quantity;
use App\Ordering\ValueObject\Pricing\Order\Sku;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

final class WorkflowInventoryPaymentIntegrationTest extends TestCase
{
    private static KernelInterface $kernel;

    public static function setUpBeforeClass(): void
    {
        self::$kernel = new TestKernel('test', true);
        self::$kernel->boot();
    }

    public static function tearDownAfterClass(): void
    {
        self::$kernel->shutdown();
    }

    public function testPlaceAndPayFlow(): void
    {
        $c = self::$kernel->getContainer();
        $em = $c->get(EntityManagerInterface::class);
        $tool = new SchemaTool($em);
        $tool->dropDatabase();
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        $order = OrderEntity::create('USD', '70.00');
        $em->persist($order);

        $item1 = new OrderItemEntity($order, new Sku('SKU-1'), new Quantity(2), 1000); // $20
        $item2 = new OrderItemEntity($order, new Sku('SKU-2'), new Quantity(1), 5000); // $50
        $em->persist($item1);
        $em->persist($item2);
        $em->flush();

        /** @var OrderWorkflowService $svc */
        $svc = $c->get(OrderWorkflowService::class);
        $svc->place($order, [$item1, $item2]);

        // pay full amount (7000 cents)
        $svc->pay($order, 7000);
        $em->refresh($order);

        $this->assertSame(OrderStatus::Paid->value, $order->getStatus());
    }
}
