<?php

declare(strict_types=1);

namespace App\Messenger\Middleware;

interface IdempotencyStoreInterface
{
    public function has(string $key): bool;

    public function put(string $key): void;
}
