<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order;
use App\Entity\Outbox\OutboxMessage;
use App\Event\Domain\Order\OrderPaidEvent;
use App\Event\Domain\Order\OrderPlacedEvent;
use App\Event\Domain\Order\OrderShippedEvent;
use App\Service\Workflow\Order\OrderWorkflowService;
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
        $order = new Order();
        $svc->place($order);
        $svc->pay($order);
        $svc->ship($order);

        // outbox has 3 messages
        $count = (int) $em->createQuery('SELECT COUNT(m.id) FROM App\Entity\Outbox\OutboxMessage m')->getSingleScalarResult();
        $this->assertSame(3, $count);

        // validate event names in outbox payloads
        $msgs = $em->getRepository(OutboxMessage::class)->findAll();
        $names = array_map(fn ($m) => $m->getEventName(), $msgs);
        $this->assertContains(OrderPlacedEvent::class, $names);
        $this->assertContains(OrderPaidEvent::class, $names);
        $this->assertContains(OrderShippedEvent::class, $names);
    }
}
