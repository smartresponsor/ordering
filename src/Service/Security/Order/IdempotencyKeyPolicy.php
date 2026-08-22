<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

namespace App\Ordering\Service\Security\Order;

use App\Ordering\ServiceInterface\Security\Order\IdempotencyKeyPolicyInterface;

final readonly class IdempotencyKeyPolicy implements IdempotencyKeyPolicyInterface
{
    public function __construct(
        private int $ttlSeconds = 600,
    ) {
    }

    public function ttlSeconds(): int
    {
        return $this->ttlSeconds;
    }

    /** @param array<string, string|array<int, string>> $header */
    public function keyForHttp(string $method, string $path, string $body, array $header): string
    {
        $token = $header['X-Idempotency-Token'] ?? '';
        $normalizedToken = is_array($token) ? implode(',', $token) : $token;
        $canon = strtoupper($method).'|'.$path.'|'.hash('sha256', $body).'|'.$normalizedToken;

        return hash('sha256', $canon);
    }

    public function keyForWorker(string $topic, string $payload): string
    {
        $canon = $topic.'|'.hash('sha256', $payload);

        return hash('sha256', $canon);
    }
}
