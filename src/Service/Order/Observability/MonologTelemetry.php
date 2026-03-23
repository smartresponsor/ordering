<?php

declare(strict_types=1);

namespace App\Service\Order\Observability;

use Psr\Log\LoggerInterface;

final class MonologTelemetry
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /** @param array<string, mixed> $context */
    public function emit(string $event, array $context = []): void
    {
        $this->logger->info($event, $context);
    }
}
