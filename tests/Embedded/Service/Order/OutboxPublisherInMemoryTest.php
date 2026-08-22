<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Ordering\Message\Outbox\OrderOutboxDispatchedMessage;
use App\Ordering\Service\Outbox\OutboxPublisher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;

final class OutboxPublisherInMemoryTest extends TestCase
{
    public function testPublishToInMemory(): void
    {
        // Arrange: create in-memory transport + bus that routes all messages to it
        $transport = new InMemoryTransport();
        $senders = new SendersLocator([
            OrderOutboxDispatchedMessage::class => ['in_memory'],
        ], new ServiceLocator([
            'in_memory' => static fn (): InMemoryTransport => $transport,
        ]));
        $bus = new MessageBus([new SendMessageMiddleware($senders)]);
        $publisher = new OutboxPublisher($bus);

        // Act
        $publisher->publish('order.event', ['foo' => 'bar']);

        // Assert
        $this->assertCount(1, $transport->get(), 'One message must be sent to in-memory transport');
        /** @var Envelope $envelope */
        $envelope = $transport->get()[0];
        $this->assertInstanceOf(OrderOutboxDispatchedMessage::class, $envelope->getMessage());
    }
}
