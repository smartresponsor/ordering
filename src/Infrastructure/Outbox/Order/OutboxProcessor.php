<?php

declare(strict_types=1);

namespace App\Infrastructure\Outbox\Order;

use App\Service\Outbox\Order\DlqPublisher;
use App\Service\Outbox\Order\ExponentialBackoffStrategy;
use App\Service\Outbox\OutboxProcessor as BaseOutboxProcessor;
use Psr\Log\LoggerInterface;

final readonly class OutboxProcessor
{
    public function __construct(
        private BaseOutboxProcessor $processor,
        private ExponentialBackoffStrategy $backoff,
        private DlqPublisher $dlq,
        private LoggerInterface $logger,
        private int $maxAttempts = 5,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function run(int $limit = 100): int
    {
        $this->logger->info('Running order outbox processor.', [
            'limit' => $limit,
            'maxAttempts' => $this->maxAttempts,
            'initialBackoffSeconds' => $this->backoff->nextDelaySeconds(1),
            'dlqPublisher' => $this->dlq::class,
        ]);

        return $this->processor->process($limit);
    }
}
