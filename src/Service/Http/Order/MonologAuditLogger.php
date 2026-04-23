<?php

declare(strict_types=1);

namespace App\Service\Http\Order;

use Psr\Log\LoggerInterface;

final readonly class MonologAuditLogger
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /** @param array<string, mixed> $context */
    public function log(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }
}
