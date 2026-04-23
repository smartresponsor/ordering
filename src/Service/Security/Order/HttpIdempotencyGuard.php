<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\ServiceInterface\Security\Order\HttpIdempotencyGuardInterface;
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

use App\ServiceInterface\Security\Order\IdempotencyStoreInterface;

final readonly class HttpIdempotencyGuard implements HttpIdempotencyGuardInterface
{
    public function __construct(
        private IdempotencyKeyPolicy $policy,
        private IdempotencyStoreInterface $store,
    ) {
    }

    /** @param array<string, string|array<int, string>> $header */
    public function allow(string $method, string $path, string $body, array $header): bool
    {
        $key = $this->policy->keyForHttp($method, $path, $body, $header);

        return $this->store->setIfAbsent($key, $this->policy->ttlSeconds());
    }
}
