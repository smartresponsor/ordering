<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Outbox\OutboxMessage;
use App\Messenger\Message\OutboxDispatchedMessage;
use App\Repository\Outbox\OutboxMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class OutboxPublisher
{
    public function __construct(
        private OutboxMessageRepository $repo,
        private EntityManagerInterface $em,
        private MessageBusInterface $bus,
    ) {
    }

    public function replay(int $limit = 100): int
    {
        $count = 0;
        foreach ($this->repo->findUnpublishedBatch($limit) as $msg) {
            $this->bus->dispatch(new OutboxDispatchedMessage($msg->getEventType(), $msg->toArray()['payload']));
            $msg->markDispatched();
            ++$count;
        }
        $this->em->flush();

        return $count;
    }

    public function storeAndPublish(string $aggregateId, string $eventType, array $payload): void
    {
        $outbox = new OutboxMessage($aggregateId, $eventType, $payload);
        $this->em->persist($outbox);
        $this->em->flush();
        $this->bus->dispatch(new OutboxDispatchedMessage($eventType, $payload));
        $outbox->markDispatched();
        $this->em->flush();
    }

    public function publish(string $topic, array $payload): void
    {
        $this->bus->dispatch(new OutboxDispatchedMessage($topic, $payload));
    }
}
