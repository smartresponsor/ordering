<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Outbox\OutboxMessage;
use App\Message\OrderEventMessage;
use App\Repository\Outbox\OutboxMessageRepository;
use App\ServiceInterface\Security\Order\OutboxProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class OutboxProcessor implements OutboxProcessorInterface
{
    public function __construct(
        private OutboxMessageRepository $repo,
        private EntityManagerInterface $em,
        private MessageBusInterface $bus,
        private LoggerInterface $logger,
    ) {
    }

    public function dispatchBatch(int $limit = 50): int
    {
        $messages = $this->repo->findUnpublishedBatch($limit);
        $count = 0;

        foreach ($messages as $message) {
            if (!$message instanceof OutboxMessage) {
                continue;
            }

            $payload = $message->payload();
            try {
                $this->bus->dispatch(new OrderEventMessage($message->getTopic(), (string) ($payload['orderId'] ?? $payload['aggregateId'] ?? '')));
                $message->markSent();
                ++$count;
            } catch (\Throwable $e) {
                $this->logger->warning('Outbox dispatch failed', ['id' => $message->messageId(), 'error' => $e->getMessage()]);
                $message->markFailed();
            }
        }

        $this->em->flush();

        return $count;
    }
}
