<?php

declare(strict_types=1);

namespace App\Messenger\Middleware;

final class InMemoryIdempotencyStore implements IdempotencyStoreInterface
{
    /** @var array<string, true> */
    private array $store = [];

    public function has(string $key): bool
    {
        return isset($this->store[$key]);
    }

    public function put(string $key): void
    {
        $this->store[$key] = true;
    }
}
