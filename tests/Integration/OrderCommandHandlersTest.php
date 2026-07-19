<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Message\Command\OrderCreateCommand;
use App\Message\Command\OrderPayCommand;
use App\Message\Handler\OrderCreateHandler;
use App\Message\Handler\OrderPayHandler;
use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use App\Service\Outbox\OutboxPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\Kernel;

final class OrderCommandHandlersTest extends TestCase
{
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();
        $this->em = $kernel->getContainer()->get(EntityManagerInterface::class);

        $tool = new SchemaTool($this->em);
        $tool->dropDatabase();
        $tool->createSchema($this->em->getMetadataFactory()->getAllMetadata());
    }

    public function testCreateAndPayProducesOutbox(): void
    {
        $create = new OrderCreateHandler($this->em);
        $bus = $this->createMock(MessageBusInterface::class);
        $bus->method('dispatch')->willReturnCallback(fn ($m) => $m);
        $outbox = new OutboxPublisher($this->em, $bus);
        $pay = new OrderPayHandler($this->em, $outbox);

        $orderId = $create(new OrderCreateCommand('USD', '100.00'));
        $this->assertNotEmpty($orderId);

        $pay(new OrderPayCommand($orderId, '40.00', 'r1'));
        $pay(new OrderPayCommand($orderId, '60.00', 'r2'));

        /** @var OrderEntity|null $order */
        $order = $this->em->getRepository(OrderEntity::class)->findByIdentifier($orderId);
        $this->assertNotNull($order);
        $this->assertSame('paid', $order->status());
        $this->assertSame('100.00', $order->paidTotal());

        $repo = $this->em->getRepository(OrderOutboxMessageEntity::class);
        $all = $repo->findAll();
        $this->assertGreaterThanOrEqual(2, count($all), 'Two payment events should be recorded to outbox');
    }
}
