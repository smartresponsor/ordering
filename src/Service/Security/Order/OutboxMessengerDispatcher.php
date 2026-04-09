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
use App\ServiceInterface\Security\Order\OutboxMessengerDispatcherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class OutboxMessengerDispatcher implements OutboxMessengerDispatcherInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MessageBusInterface $bus,
    ) {
    }

    public function dispatchPending(int $limit = 100): int
    {
        $repo = $this->em->getRepository(OutboxMessage::class);
        $messages = array_filter(
            $repo->findBy([], ['messageId' => 'ASC'], $limit),
            static fn (mixed $message): bool => $message instanceof OutboxMessage
                && $message->isPending(),
        );
        $count = 0;

        foreach ($messages as $message) {
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
