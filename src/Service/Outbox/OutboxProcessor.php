<?php

declare(strict_types=1);

namespace App\Ordering\Service\Outbox;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use App\Ordering\Event\Domain\Order\OrderCancelledEvent;
use App\Ordering\Event\Domain\Order\OrderPaidEvent;
use App\Ordering\Event\Domain\Order\OrderPlacedEvent;
use App\Ordering\Event\Domain\Order\OrderRefundedEvent;
use App\Ordering\Event\Domain\Order\OrderShippedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class OutboxProcessor
{
    public function __construct(
        private EntityManagerInterface $em,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function process(int $limit = 100): int
    {
        $repo = $this->em->getRepository(OrderOutboxMessageEntity::class);
        $messages = array_filter(
            $repo->findBy([], ['id' => 'ASC'], $limit),
            static fn (mixed $message): bool => $message instanceof OrderOutboxMessageEntity
                && $message->isPending(),
        );
        $count = 0;

        foreach ($messages as $message) {
            $payload = $message->payload();
            $eventName = $message->getEventType();
            $orderId = (int) ($payload['orderId'] ?? $payload['aggregateId'] ?? 0);
            $event = match ($eventName) {
                OrderPlacedEvent::class => new OrderPlacedEvent((string) $orderId),
                OrderPaidEvent::class => new OrderPaidEvent(
                    (string) $orderId,
                    (string) ($payload['amount'] ?? '0.00'),
                    (string) ($payload['currency'] ?? 'USD'),
                    (string) ($payload['externalRef'] ?? $payload['txId'] ?? ''),
                ),
                OrderShippedEvent::class => new OrderShippedEvent((string) $orderId),
                OrderCancelledEvent::class => new OrderCancelledEvent($this->findOrder($orderId)),
                OrderRefundedEvent::class => new OrderRefundedEvent(
                    $this->findOrder($orderId),
                    (string) ($payload['amount'] ?? '0.00'),
                ),
                default => new class($orderId, $eventName) {
                    public function __construct(public int $orderId, public string $class)
                    {
                    }
                },
            };

            $this->dispatcher->dispatch($event, $eventName);
            $message->markDispatched();
            ++$count;
        }

        $this->em->flush();

        return $count;
    }

    private function findOrder(int $orderId): OrderEntity
    {
        $order = $this->em->getRepository(OrderEntity::class)->find($orderId);
        if (!$order instanceof OrderEntity) {
            throw new \RuntimeException('Order not found for outbox event: '.$orderId);
        }

        return $order;
    }
}
