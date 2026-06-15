<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order\OrderOutboxMessageEntity;
use App\Message\Outbox\OrderOutboxDispatchedMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class OutboxMessengerDispatcher
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageBusInterface $bus,
    ) {
    }

    public function dispatchPending(int $limit = 100): int
    {
        $repo = $this->em->getRepository(OrderOutboxMessageEntity::class);
        $messages = array_filter(
            $repo->findBy([], ['messageId' => 'ASC'], $limit),
            static fn (mixed $message): bool => $message instanceof OrderOutboxMessageEntity
                && $message->isPending(),
        );
        $count = 0;

        foreach ($messages as $message) {
            $payload = $message->payload();
            $this->bus->dispatch(new OrderOutboxDispatchedMessage($message->getTopic(), $payload));
            $message->markSent();
            ++$count;
        }

        $this->em->flush();

        return $count;
    }
}
