<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderItemEntity;
use App\Service\Workflow\Order\OrderWorkflowService;
use App\ValueObject\Pricing\Order\Quantity;
use App\ValueObject\Pricing\Order\Sku;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

final class WorkflowPricingIntegrationTest extends TestCase
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

    public function testPlaceWithPricing(): void
    {
        $c = self::$kernel->getContainer();
        $em = $c->get(EntityManagerInterface::class);
        $tool = new SchemaTool($em);
        $tool->dropDatabase();
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        $order = OrderEntity::create('USD', '0.00');
        $em->persist($order);

        $item1 = new OrderItemEntity($order, new Sku('SKU-1'), new Quantity(2), 1000); // $10 x2 = $20 => 2000 cents
        $item2 = new OrderItemEntity($order, new Sku('SKU-2'), new Quantity(1), 5000); // $50 => 5000 cents
        $em->persist($item1);
        $em->persist($item2);
        $em->flush();

        /** @var OrderWorkflowService $svc */
        $svc = $c->get(OrderWorkflowService::class);
        $svc->place($order, [$item1, $item2]);
        $em->refresh($order);

        $this->assertSame(0, bccomp((string) $order->getSubtotal(), '70.00', 2));
    }
}
