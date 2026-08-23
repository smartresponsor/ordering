<?php

declare(strict_types=1);

namespace App\Test\Smoke;

use App\Ordering\Service\Security\Order\HttpIdempotencyGuard;
use App\Ordering\Service\Security\Order\IdempotencyKeyPolicy;
use App\Ordering\Service\Security\Order\MemoryIdempotencyStore;
use PHPUnit\Framework\TestCase;

/**
 * Basic smoke coverage for the HTTP idempotency guard.
 */
final class IdempotencyTest extends TestCase
{
    public function testHttpIdempotencyGuard(): void
    {
        $policy = new IdempotencyKeyPolicy(1);
        $store = new MemoryIdempotencyStore();
        $guard = new HttpIdempotencyGuard($policy, $store);

        $accepted = $guard->allow('POST', '/x', '{"a":1}', ['X-Idempotency-Token' => 't']);
        $duplicate = $guard->allow('POST', '/x', '{"a":1}', ['X-Idempotency-Token' => 't']);

        self::assertTrue($accepted);
        self::assertFalse($duplicate);
    }
}
