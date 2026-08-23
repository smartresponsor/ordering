<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use App\Ordering\Event\Domain\Order\OrderPaidEvent;
use App\Ordering\Event\Domain\Order\OrderPlacedEvent;
use App\Ordering\Event\Domain\Order\OrderShippedEvent;
use App\Ordering\Service\Workflow\Order\OrderWorkflowService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

final class OrderWorkflowTest extends TestCase
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

    public function testWorkflowAndOutbox(): void
    {
        $c = self::$kernel->getContainer();
        $em = $c->get(EntityManagerInterface::class);
        $tool = new SchemaTool($em);
        $tool->dropDatabase();
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        /** @var OrderWorkflowService $svc */
        $svc = $c->get(OrderWorkflowService::class);
        $order = OrderEntity::create('USD', '100.00');
        $svc->place($order, []);
        $svc->pay($order, 10000);
        $svc->ship($order);

        // outbox has 3 messages
        $count = (int) $em->createQuery('SELECT COUNT(m.id) FROM App\Ordering\Entity\Order\OrderOutboxMessageEntity m')->getSingleScalarResult();
        $this->assertSame(3, $count);

        // validate event names in outbox payloads
        $msgs = $em->getRepository(OrderOutboxMessageEntity::class)->findAll();
        $names = array_map(fn ($m) => $m->getEventType(), $msgs);
        $this->assertContains(OrderPlacedEvent::class, $names);
        $this->assertContains(OrderPaidEvent::class, $names);
        $this->assertContains(OrderShippedEvent::class, $names);
    }
}
