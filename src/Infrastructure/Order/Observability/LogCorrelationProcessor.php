<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Observability;

final class LogCorrelationProcessor
{
    /**
     * @param array<string, mixed> $record
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $record): array
    {
        $record['extra'] ??= [];
        if (!isset($record['extra']['correlation_id'])) {
            $record['extra']['correlation_id'] = RequestCorrelationListener::currentCorrelationId();
        }

        return $record;
    }
}
