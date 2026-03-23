<?php

declare(strict_types=1);

namespace App\Entity\Order;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@highhopesamerica.com>.
 */
final class OutboxRecord
{
    public int $attempt = 0;
    public int $maxAttempt = 5;
    public float $nextAt;
    public string $status = 'pending';

    public function __construct(
        public string $id,
        public string $event,
        public array $payload,
    ) {
        $this->nextAt = microtime(true);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'event' => $this->event,
            'payload' => $this->payload,
            'attempt' => $this->attempt,
            'maxAttempt' => $this->maxAttempt,
            'nextAt' => $this->nextAt,
            'status' => $this->status,
        ];
    }

    public static function fromArray(array $data): self
    {
        $record = new self(
            (string) ($data['id'] ?? ''),
            (string) ($data['event'] ?? ''),
            (array) ($data['payload'] ?? []),
        );
        $record->attempt = (int) ($data['attempt'] ?? 0);
        $record->maxAttempt = (int) ($data['maxAttempt'] ?? 5);
        $record->nextAt = (float) ($data['nextAt'] ?? microtime(true));
        $record->status = (string) ($data['status'] ?? 'pending');

        return $record;
    }
}
