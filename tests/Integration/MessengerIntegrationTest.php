<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order\OrderEntity;
use App\Message\OrderMessage;
use App\Service\Workflow\Order\OrderWorkflowService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

final class MessengerIntegrationTest extends TestCase
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

    public function testPublishAndIdempotency(): void
    {
        $c = self::$kernel->getContainer();
        $em = $c->get(EntityManagerInterface::class);
        $tool = new SchemaTool($em);
        $tool->dropDatabase();
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        /** @var OrderWorkflowService $svc */
        $svc = $c->get(OrderWorkflowService::class);

        $o = new OrderEntity();
        $em->persist($o);
        $em->flush();

        $svc->place($o); // should publish OrderPlaced → handled synchronously in test via sync transport
        // publish duplicate
        $bus = $c->get('messenger.default_bus');
        $bus->dispatch(new OrderMessage('App\Event\Domain\Order\OrderPlacedEvent', $o->getId()));

        $count = (int) $em->createQuery('SELECT COUNT(k.key) FROM App\Entity\Outbox\IdempotencyKey k')->getSingleScalarResult();
        $this->assertSame(1, $count, 'Idempotency stored only once');
    }
}
