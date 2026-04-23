<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Outbox\Order;

use App\RepositoryInterface\Order\OutboxRepositoryInterface;
use App\ServiceInterface\Messaging\Order\TransactionalEventPublisherInterface;
use App\ServiceInterface\Outbox\Order\OutboxRelayInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

final readonly class OutboxRelay implements OutboxRelayInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OutboxRepositoryInterface $repo,
        private TransactionalEventPublisherInterface $publisher,
        private LoggerInterface $logger,
    ) {
    }

    public function runOnce(int $batchSize = 50): int
    {
        $processed = 0;

        foreach ($this->repo->pullPending($batchSize) as $msg) {
            try {
                $this->publisher->relay($msg);
                $this->repo->markSent($msg);
                ++$processed;
            } catch (\Throwable $e) {
                $this->logger->error('Outbox relay failed', ['error' => $e->getMessage()]);
                $delay = max(10, ($msg->attempts() + 1) ** 2 * 10);
                $this->repo->markFailed($msg, $delay);
            }
        }

        $this->em->flush();

        return $processed;
    }
}
