<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

interface IdempotencyKeyPolicyInterface
{
    public function ttlSeconds(): int;

    public function keyForHttp(string $method, string $path, string $body, array $header): string;

    public function keyForWorker(string $topic, string $payload): string;
}
