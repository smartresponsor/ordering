<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

namespace App\Service\Security\Order;

use App\ServiceInterface\Security\Order\IdempotencyKeyPolicyInterface;

final class IdempotencyKeyPolicy implements IdempotencyKeyPolicyInterface
{
    public function __construct(private int $ttlSeconds = 600)
    {
    }

    public function ttlSeconds(): int
    {
        return $this->ttlSeconds;
    }

    public function keyForHttp(string $method, string $path, string $body, array $header): string
    {
        $canon = strtoupper($method).'|'.$path.'|'.hash('sha256', $body).'|'.($header['X-Idempotency-Token'] ?? '');

        return hash('sha256', $canon);
    }

    public function keyForWorker(string $topic, string $payload): string
    {
        $canon = $topic.'|'.hash('sha256', $payload);

        return hash('sha256', $canon);
    }
}
