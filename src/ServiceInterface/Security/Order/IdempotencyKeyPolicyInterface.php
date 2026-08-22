<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Ordering\ServiceInterface\Security\Order;

interface IdempotencyKeyPolicyInterface
{
    public function ttlSeconds(): int;

    /** @param array<string, string|array<int, string>> $header */
    public function keyForHttp(string $method, string $path, string $body, array $header): string;

    public function keyForWorker(string $topic, string $payload): string;
}
