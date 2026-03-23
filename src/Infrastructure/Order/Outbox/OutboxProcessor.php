<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Outbox;

use App\Service\Order\Outbox\DlqPublisher;
use App\Service\Order\Outbox\ExponentialBackoffStrategy;
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
        ]);

        return $this->processor->process($limit);
    }
}
