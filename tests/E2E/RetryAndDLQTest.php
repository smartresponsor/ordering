<?php

declare(strict_types=1);

namespace Tests\E2E;

use App\Entity\Order;
use App\Event\Domain\Order\OrderShippedEvent;
use App\Service\Outbox\OutboxMessengerDispatcher;
use App\Service\Outbox\OutboxPublisher;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\EventListener\StopWorkerOnMessageLimitListener;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Symfony\Component\Messenger\Worker;

final class RetryAndDLQTest extends TestCase
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

    public function testFailureGoesToDeadLetter(): void
    {
        $c = self::$kernel->getContainer();
        $em = $c->get(EntityManagerInterface::class);
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $tool->dropDatabase();
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        $order = new Order();
        $em->persist($order);
        $em->flush();
        /** @var OutboxPublisher $pub */ $pub = $c->get(OutboxPublisher::class);
        $pub->publish(OrderShippedEvent::class, ['orderId' => $order->getId()]);
        $em->flush();

        /** @var OutboxMessengerDispatcher $disp */ $disp = $c->get(OutboxMessengerDispatcher::class);
        $this->assertSame(1, $disp->dispatchPending());

        /** @var InMemoryTransport $async */ $async = $c->get('app.test_messenger.transport.async');
        /** @var InMemoryTransport $failed */ $failed = $c->get('app.test_messenger.transport.failed');

        // Run worker to consume messages with retries; use container's event dispatcher so failure listener is active
        $bus = $c->get('messenger.default_bus');
        $dispatcher = $c->get('event_dispatcher');
        $dispatcher->addSubscriber(new StopWorkerOnMessageLimitListener(1));
        $worker = new Worker(['async' => $async], $bus, $dispatcher, new NullLogger());
        $worker->run(['sleep' => 1000]); // one failed attempt is enough with test retry settings

        $this->assertCount(0, $async->get(), 'Async queue should be drained');
        $this->assertGreaterThanOrEqual(1, count($failed->getSent()), 'Failed queue should contain the message');
    }
}
