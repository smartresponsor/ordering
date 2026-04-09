<?php

declare(strict_types=1);

namespace App\Service\Observability\Order;

use Psr\Log\LoggerInterface;

final readonly class MonologApiMetric
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /** @param array<string, mixed> $context */
    public function record(array $context): void
    {
        $this->logger->info('order.api.metric', $context);
    }
}
