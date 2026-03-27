<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Entity\Order\Order;
use App\Entity\Outbox\OutboxMessage;
use App\Message\Command\OrderCreateCommand;
use App\Message\Command\OrderPayCommand;
use App\Message\Handler\OrderCreateHandler;
use App\Message\Handler\OrderPayHandler;
use App\Service\Outbox\OutboxPublisher;
use Doctrine\ORM\EntityManagerInterface;
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

        /** @var Order $order */
        $order = $this->em->getRepository(Order::class)->findOneBy(['id' => $orderId]);
        $this->assertSame('paid', $order->status());
        $this->assertSame('100.00', $order->paidTotal());

        $repo = $this->em->getRepository(OutboxMessage::class);
        $all = $repo->findAll();
        $this->assertGreaterThanOrEqual(2, count($all), 'Two payment events should be recorded to outbox');
    }
}
