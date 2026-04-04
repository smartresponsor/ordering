<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order\OutboxMessage;
use App\Message\OrderEventMessage;
use App\ServiceInterface\Order\OutboxMessengerDispatcherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OutboxMessengerDispatcher implements OutboxMessengerDispatcherInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MessageBusInterface $bus,
    ) {
    }

    public function dispatchPending(int $limit = 100): int
    {
        $repo = $this->em->getRepository(OutboxMessage::class);
        $messages = $repo->findBy(['status' => OutboxMessage::STATUS_PENDING], ['occurredAt' => 'ASC'], $limit);
        $count = 0;

        foreach ($messages as $message) {
            if (!$message instanceof OutboxMessage) {
                continue;
            }

            $payload = $message->payload();
            $orderId = (string) ($payload['orderId'] ?? $payload['aggregateId'] ?? '');
            $this->bus->dispatch(new OrderEventMessage($message->getTopic(), $orderId));
            $message->markSent();
            ++$count;
        }

        $this->em->flush();

        return $count;
    }
}
