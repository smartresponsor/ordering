<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ServiceInterface\Order\IdempotencyStoreInterface;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class MemoryIdempotencyStore implements IdempotencyStoreInterface
{
    /** @var array<string,int> */
    private array $ttl;

    public function __construct()
    {
        $this->ttl = [];
    }

    public function setIfAbsent(string $keyHash, int $ttlSeconds): bool
    {
        $now = time();
        $this->gc($now);
        if (isset($this->ttl[$keyHash]) && $this->ttl[$keyHash] > $now) {
            return false;
        }
        $this->ttl[$keyHash] = $now + $ttlSeconds;

        return true;
    }

    public function has(string $keyHash): bool
    {
        $now = time();
        $this->gc($now);

        return isset($this->ttl[$keyHash]) && $this->ttl[$keyHash] > $now;
    }

    private function gc(int $now): void
    {
        foreach ($this->ttl as $k => $t) {
            if ($t <= $now) {
                unset($this->ttl[$k]);
            }
        }
    }
}
