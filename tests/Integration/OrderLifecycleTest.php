<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\ValueObject\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

final class OrderLifecycleTest extends TestCase
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

    public function testCreateAndTransition(): void
    {
        $container = self::$kernel->getContainer();
        $em = $container->get(EntityManagerInterface::class);

        // Ensure schema exists
        $schemaTool = new SchemaTool($em);
        $classes = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);

        $order = OrderEntity::create('USD', '0.00');
        $em->persist($order);
        $em->flush();

        self::assertNotNull($order->getId());

        $order->setStatus(OrderStatus::Placed);
        $em->flush();
        self::assertSame(OrderStatus::Placed->value, $order->getStatus());
    }
}
