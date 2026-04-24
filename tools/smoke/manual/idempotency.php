#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Service\Security\Order\HttpIdempotencyGuard;
use App\Service\Security\Order\IdempotencyKeyPolicy;
use App\Service\Security\Order\MemoryIdempotencyStore;

/**
 * CLI smoke check for the current idempotency guard implementation.
 */
require __DIR__ . '/../vendor/autoload.php';

$guard = new HttpIdempotencyGuard(new IdempotencyKeyPolicy(1), new MemoryIdempotencyStore());
assert($guard->allow('POST', '/x', '{"a":1}', ['X-Idempotency-Token' => 't']) === true);
assert($guard->allow('POST', '/x', '{"a":1}', ['X-Idempotency-Token' => 't']) === false);
echo "OK\n";
