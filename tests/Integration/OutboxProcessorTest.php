<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order\OrderOutboxMessageEntity;
use App\Service\Outbox\OutboxProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class OutboxProcessorTest extends TestCase
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

    public function testProcessMarksPendingMessagesDispatched(): void
    {
        $c = self::$kernel->getContainer();
        $em = $c->get(EntityManagerInterface::class);
        $tool = new SchemaTool($em);
        $tool->dropDatabase();
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        // Seed three pending messages.
        $m1 = new OrderOutboxMessageEntity('OrderPaidEvent', '{"orderId":1}');
        $m2 = new OrderOutboxMessageEntity('OrderPaidEvent', '{"orderId":1}');
        $mf = new OrderOutboxMessageEntity('OrderShippedEvent', '{"orderId":2}');
        $em->persist($m1);
        $em->persist($m2);
        $em->persist($mf);
        $em->flush();

        $seen = [];
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects($this->exactly(3))
            ->method('dispatch')
            ->willReturnCallback(function (object $event, ?string $eventName = null) use (&$seen): object {
                $seen[] = $eventName;

                return $event;
            });

        $proc = new OutboxProcessor($em, $dispatcher);

        $processed = $proc->process(50);
        $this->assertSame(3, $processed, 'All pending messages should be processed');
        $this->assertSame(['OrderPaidEvent', 'OrderPaidEvent', 'OrderShippedEvent'], $seen);

        $msgs = $em->getRepository(OrderOutboxMessageEntity::class)->findAll();
        $this->assertCount(3, $msgs);
        foreach ($msgs as $message) {
            $this->assertTrue($message->isDispatched());
        }
    }
}
