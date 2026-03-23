<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order;
use App\Entity\Order\OrderItem;
use App\Service\Order\OrderWorkflowService;
use App\Service\Outbox\OutboxMessengerDispatcher;
use App\ValueObject\Money\Currency;
use App\ValueObject\Order\Quantity;
use App\ValueObject\Order\Sku;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

final class OutboxMessengerIntegrationTest extends TestCase
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

    public function testWorkflowToQueue(): void
    {
        $c = self::$kernel->getContainer();
        $em = $c->get(EntityManagerInterface::class);
        $tool = new SchemaTool($em);
        $tool->dropDatabase();
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        $o = new Order();
        $o->setCurrency(new Currency('USD'));
        $em->persist($o);
        $i1 = new OrderItem($o, new Sku('SKU-1'), new Quantity(2), 1000);
        $i2 = new OrderItem($o, new Sku('SKU-2'), new Quantity(1), 5000);
        $em->persist($i1);
        $em->persist($i2);
        $em->flush();

        $wf = $c->get(OrderWorkflowService::class);
        $wf->place($o, [$i1, $i2]);
        $wf->pay($o, 7000);
        $wf->ship($o);

        $disp = $c->get(OutboxMessengerDispatcher::class);
        $dispatched = $disp->dispatchPending();
        $this->assertGreaterThanOrEqual(3, $dispatched);

        /** @var InMemoryTransport $async */ $async = $c->get('messenger.transport.async');
        $this->assertGreaterThanOrEqual(3, count($async->getSent()));
    }
}
