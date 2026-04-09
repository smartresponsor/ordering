<?php

declare(strict_types=1);

namespace App\Entity\Order;

final readonly class OrderEventRecord
{
    public function __construct(
        private readonly string $eventId,
        private readonly string $orderId,
        private readonly string $eventName,
        private readonly array $payload = [],
        private readonly ?\DateTimeImmutable $occurredAt = null,
    ) {
        $this->occurredAt ??= new \DateTimeImmutable();
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function eventName(): string
    {
        return $this->eventName;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt ?? new \DateTimeImmutable();
    }
}
