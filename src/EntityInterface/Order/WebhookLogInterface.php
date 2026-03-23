<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface WebhookLogInterface
{
    public function __construct(string $key, string $eventType, string $payload);

    public function getId(): int;

    public function getIdempotencyKey(): string;

    public function getEventType(): string;

    public function getPayload(): string;

    public function getReceivedAt(): \DateTimeImmutable;
}
