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
use App\Ordering\Repository\Order\OrderRepository;
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
            $orderIdentifier = trim((string) ($payload['orderId'] ?? $payload['aggregateId'] ?? ''));
            $event = match ($eventName) {
                OrderPlacedEvent::class => new OrderPlacedEvent($orderIdentifier),
                OrderPaidEvent::class => new OrderPaidEvent(
                    $orderIdentifier,
                    (string) ($payload['amount'] ?? '0.00'),
                    (string) ($payload['currency'] ?? 'USD'),
                    (string) ($payload['externalRef'] ?? $payload['txId'] ?? ''),
                ),
                OrderShippedEvent::class => new OrderShippedEvent(
                    $orderIdentifier,
                    isset($payload['carrier']) ? (string) $payload['carrier'] : null,
                    isset($payload['trackingCode']) ? (string) $payload['trackingCode'] : null,
                ),
                OrderCancelledEvent::class => new OrderCancelledEvent(
                    $orderIdentifier,
                    isset($payload['vendorId']) ? (string) $payload['vendorId'] : null,
                ),
                OrderRefundedEvent::class => new OrderRefundedEvent(
                    $orderIdentifier,
                    (string) ($payload['amount'] ?? '0.00'),
                    (string) ($payload['currency'] ?? ''),
                    isset($payload['vendorId']) ? (string) $payload['vendorId'] : null,
                ),
                default => new class($orderIdentifier, $eventName) {
                    public function __construct(public string $orderId, public string $class)
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

    private function findOrder(string|int $orderIdentifier): OrderEntity
    {
        /** @var OrderRepository $repository */
        $repository = $this->em->getRepository(OrderEntity::class);
        $order = $repository->findByIdentifier($orderIdentifier);
        if (!$order instanceof OrderEntity) {
            throw new \RuntimeException('Order not found for outbox event: '.$orderIdentifier);
        }

        return $order;
    }
}
