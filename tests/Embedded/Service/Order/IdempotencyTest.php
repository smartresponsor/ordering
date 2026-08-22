<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

use App\Ordering\Service\Security\Order\HttpIdempotencyGuard;
use App\Ordering\Service\Security\Order\IdempotencyKeyPolicy;
use App\Ordering\Service\Security\Order\MemoryIdempotencyStore;
use PHPUnit\Framework\TestCase;

final class IdempotencyTest extends TestCase
{
    public function testHttpIdempotencyGuard(): void
    {
        $policy = new IdempotencyKeyPolicy(1);
        $store = new MemoryIdempotencyStore();
        $guard = new HttpIdempotencyGuard($policy, $store);

        $accepted = $guard->allow('POST', '/x', '{"a":1}', ['X-Idempotency-Token' => 't']);
        $duplicate = $guard->allow('POST', '/x', '{"a":1}', ['X-Idempotency-Token' => 't']);

        $this->assertTrue($accepted);
        $this->assertFalse($duplicate);
    }
}
