<?php

declare(strict_types=1);

namespace App\Service\Order\Http;

use Psr\Log\LoggerInterface;

final class MonologAuditLogger
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /** @param array<string, mixed> $context */
    public function log(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }
}
