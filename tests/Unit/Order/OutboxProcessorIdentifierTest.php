<?php

declare(strict_types=1);

namespace App\Ordering\Tests\Unit\Order;

use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use App\Ordering\Event\Domain\Order\OrderPlacedEvent;
use App\Ordering\Service\Outbox\OutboxProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class OutboxProcessorIdentifierTest extends TestCase
{
    public function testPreservesUuidOrderIdentifierWhenDispatchingPlacedEvent(): void
    {
        $orderId = '0198f6e1-7b9e-7db8-8ad8-bf2b7937070f';
        $message = new OrderOutboxMessageEntity($orderId, OrderPlacedEvent::class, ['orderId' => $orderId]);

        $repository = $this->createMock(EntityRepository::class);
        $repository->expects(self::once())
            ->method('findBy')
            ->with([], ['id' => 'ASC'], 100)
            ->willReturn([$message]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())
            ->method('getRepository')
            ->with(OrderOutboxMessageEntity::class)
            ->willReturn($repository);
        $entityManager->expects(self::once())->method('flush');

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects(self::once())
            ->method('dispatch')
            ->with(
                self::callback(static fn (object $event): bool => $event instanceof OrderPlacedEvent && $event->orderId === $orderId),
                OrderPlacedEvent::class,
            )
            ->willReturnArgument(0);

        self::assertSame(1, (new OutboxProcessor($entityManager, $dispatcher))->process());
        self::assertTrue($message->isDispatched());
    }
}
