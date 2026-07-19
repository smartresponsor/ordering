<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Command\ClearOrdersCommand;
use App\Command\GenerateOrdersCommand;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

final class GenerateOrdersExtendedTest extends TestCase
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

    public function testGenerateAllOptions(): void
    {
        $c = self::$kernel->getContainer();
        $em = $c->get(EntityManagerInterface::class);
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());

        $app = new Application();
        $app->add($c->get(ClearOrdersCommand::class));
        $app->add($c->get(GenerateOrdersCommand::class));

        (new CommandTester($app->find('order:clear')))->execute([]);

        $tester = new CommandTester($app->find('order:generate'));
        $tester->execute([
            'count' => 4,
            '--status' => 'paid',
            '--seed' => 42,
            '--amount' => 1500,
            '--currency' => 'USD',
            '--vendor' => 'VENDOR-XYZ',
            '--with-payment' => true,
            '--with-shipment' => true,
        ]);
        $out = $tester->getDisplay();

        $this->assertStringContainsString('Created 4 orders', $out);
        $this->assertStringContainsString('Status: paid', $out);
        $this->assertStringContainsString('Vendor: VENDOR-XYZ', $out);
        $this->assertStringContainsString('Payment amount: $1500', $out);
        $this->assertStringContainsString('Payment total: $6000 (Общий платёж: $6000)', $out);

        $orders = (int) $em->createQuery('SELECT COUNT(o.id) FROM App\Entity\Order o')->getSingleScalarResult();
        $payments = (int) $em->createQuery('SELECT COUNT(p.id) FROM App\Ordering\Entity\Order\OrderPaymentEntity p')->getSingleScalarResult();
        $shipments = (int) $em->createQuery('SELECT COUNT(s.id) FROM App\Ordering\Entity\Order\OrderShipmentEntity s')->getSingleScalarResult();
        $sum = (int) $em->createQuery('SELECT COALESCE(SUM(p.amount),0) FROM App\Ordering\Entity\Order\OrderPaymentEntity p')->getSingleScalarResult();

        $this->assertSame(4, $orders);
        $this->assertSame(4, $payments);
        $this->assertSame(4, $shipments);
        $this->assertSame(6000, $sum);
    }
}
