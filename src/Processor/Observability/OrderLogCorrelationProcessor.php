<?php

declare(strict_types=1);

namespace App\Processor\Observability;

final class OrderLogCorrelationProcessor
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
            $extra['correlation_id'] = \App\EventListener\Observability\OrderRequestCorrelationListener::currentCorrelationId();
        }

        $record['extra'] = $extra;

        return $record;
    }
}
