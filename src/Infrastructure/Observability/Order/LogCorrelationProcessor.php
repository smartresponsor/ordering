<?php

declare(strict_types=1);

namespace App\Infrastructure\Observability\Order;

final class LogCorrelationProcessor
{
    /**
     * @param array<string, mixed> $record
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $record): array
    {
        $extra = $record['extra'] ?? [];
        if (!is_array($extra)) {
            $extra = [];
        }

        if (!array_key_exists('correlation_id', $extra)) {
            $extra['correlation_id'] = RequestCorrelationListener::currentCorrelationId();
        }

        $record['extra'] = $extra;

        return $record;
    }
}
