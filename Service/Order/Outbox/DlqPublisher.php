<?php

declare(strict_types=1);

namespace App\Service\Order\Outbox;

use Psr\Log\LoggerInterface;

final readonly class DlqPublisher
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function publish(string $topic, array $payload, string $reason, int $attempt = 0): void
    {
        $this->logger->warning('Order message routed to DLQ.', [
            'topic' => $topic,
            'payload' => $payload,
            'reason' => $reason,
            'attempt' => $attempt,
        ]);
    }
}
