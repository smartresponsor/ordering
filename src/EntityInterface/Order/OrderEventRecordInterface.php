<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderEventRecordInterface
{
    public function __construct(string $eventId, string $orderId, string $eventName, array $payload, ?\DateTimeImmutable $occurredAt = null);

    public function id(): int;

    public function eventId(): string;

    public function orderId(): string;

    public function eventName(): string;

    public function payload(): array;

    public function occurredAt(): \DateTimeImmutable;
}
