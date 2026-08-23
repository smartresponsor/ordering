<?php

declare(strict_types=1);

namespace App\Ordering\Processor\Outbox;

use App\Ordering\Service\Outbox\Order\DlqPublisher;
use App\Ordering\Service\Outbox\Order\ExponentialBackoffStrategy;
use App\Ordering\Service\Outbox\OutboxProcessor as BaseOutboxProcessor;
use Psr\Log\LoggerInterface;

final readonly class OrderOutboxProcessor
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
