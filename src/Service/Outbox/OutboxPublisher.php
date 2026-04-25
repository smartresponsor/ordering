<?php

declare(strict_types=1);

namespace App\Service\Outbox;

use App\Entity\Outbox\OutboxMessage;
use App\Messenger\Message\OutboxDispatchedMessage;
use App\Repository\Outbox\OutboxMessageRepository;
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
    public function storeAndPublish(string $aggregateId, string $eventType, array $payload): void
    {
        if (null !== $this->em) {
            $outbox = new OutboxMessage($aggregateId, $eventType, $payload);
            $this->em->persist($outbox);
            $this->em->flush();
            $this->bus->dispatch(new OutboxDispatchedMessage($eventType, $payload));
            $outbox->markDispatched();
            $this->em->flush();

            return;
        }

        $this->bus->dispatch(new OutboxDispatchedMessage($eventType, $payload));
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
            $this->bus->dispatch(new OutboxDispatchedMessage($message->getEventType(), $payload));
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
            $aggregateId = (string) ($payload['orderId'] ?? $payload['aggregateId'] ?? $topic);
            $this->em->persist(new OutboxMessage($aggregateId, $topic, $payload));
            $this->em->flush();

            return;
        }

        $this->bus->dispatch(new OutboxDispatchedMessage($topic, $payload));
    }
}
