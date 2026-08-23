<?php

declare(strict_types=1);

namespace App\Ordering\Service\Outbox;

use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use App\Ordering\Message\Outbox\OrderOutboxDispatchedMessage;
use App\Ordering\Repository\Outbox\OutboxMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OutboxPublisher
{
    private ?OutboxMessageRepository $repository;
    private ?EntityManagerInterface $em;
    private MessageBusInterface $bus;

    public function __construct(mixed $arg1, mixed $arg2 = null, mixed $arg3 = null)
    {
        $this->repository = null;
        $this->em = null;

        if ($arg1 instanceof MessageBusInterface && null === $arg2 && null === $arg3) {
            $this->bus = $arg1;

            return;
        }

        if ($arg1 instanceof EntityManagerInterface && $arg2 instanceof MessageBusInterface && null === $arg3) {
            $this->em = $arg1;
            $this->bus = $arg2;

            return;
        }

        if ($arg1 instanceof OutboxMessageRepository && $arg2 instanceof EntityManagerInterface && $arg3 instanceof MessageBusInterface) {
            $this->repository = $arg1;
            $this->em = $arg2;
            $this->bus = $arg3;

            return;
        }

        throw new \InvalidArgumentException('Unsupported OutboxPublisher constructor signature.');
    }

    /** @throws ExceptionInterface|\JsonException */
    public function storeAndPublish(string|int $aggregateId, string $eventType, array $payload): void
    {
        if (null !== $this->em) {
            $outbox = new OrderOutboxMessageEntity($aggregateId, $eventType, $payload);
            $this->em->persist($outbox);
            $this->em->flush();
            $this->bus->dispatch(new OrderOutboxDispatchedMessage($eventType, $payload));
            $outbox->markDispatched();
            $this->em->flush();

            return;
        }

        $this->bus->dispatch(new OrderOutboxDispatchedMessage($eventType, $payload));
    }

    /** @throws ExceptionInterface|\JsonException */
    public function replay(int $limit = 100): int
    {
        if (null === $this->repository || null === $this->em) {
            return 0;
        }

        $count = 0;
        foreach ($this->repository->findUnpublishedBatch($limit) as $message) {
            $payload = $message->toArray()['payload'];
            $this->bus->dispatch(new OrderOutboxDispatchedMessage($message->getEventType(), $payload));
            $message->markDispatched();
            ++$count;
        }
        $this->em->flush();

        return $count;
    }

    /** @throws ExceptionInterface */
    public function publish(string $topic, array $payload): void
    {
        if (null !== $this->em) {
            $aggregateId = $payload['orderId'] ?? $payload['aggregateId'] ?? $topic;
            $this->em->persist(new OrderOutboxMessageEntity($aggregateId, $topic, $payload));
            $this->em->flush();

            return;
        }

        $this->bus->dispatch(new OrderOutboxDispatchedMessage($topic, $payload));
    }
}
