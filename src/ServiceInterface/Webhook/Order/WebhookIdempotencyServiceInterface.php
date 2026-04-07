<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Webhook\Order;

interface WebhookIdempotencyServiceInterface
{
    public function handleOnce(string $key, string $eventType, string $payload, callable $callback): bool;
}
