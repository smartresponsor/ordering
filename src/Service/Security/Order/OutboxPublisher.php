<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Security\Order;

use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use App\Ordering\Message\Outbox\OrderOutboxDispatchedMessage;
use App\Ordering\Repository\Outbox\OutboxMessageRepository;
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
            $this->bus->dispatch(new OrderOutboxDispatchedMessage($msg->getEventType(), $msg->toArray()['payload']));
            $msg->markDispatched();
            ++$count;
        }
        $this->em->flush();

        return $count;
    }

    public function storeAndPublish(string|int $aggregateId, string $eventType, array $payload): void
    {
        $outbox = new OrderOutboxMessageEntity($aggregateId, $eventType, $payload);
        $this->em->persist($outbox);
        $this->em->flush();
        $this->bus->dispatch(new OrderOutboxDispatchedMessage($eventType, $payload));
        $outbox->markDispatched();
        $this->em->flush();
    }

    public function publish(string $topic, array $payload): void
    {
        $aggregateId = $payload['orderId'] ?? $payload['aggregateId'] ?? $topic;
        $outbox = new OrderOutboxMessageEntity($aggregateId, $topic, $payload);
        $this->em->persist($outbox);
        $this->em->flush();

        $this->bus->dispatch(new OrderOutboxDispatchedMessage($topic, $payload));
    }
}
