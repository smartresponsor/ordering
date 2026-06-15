<?php

declare(strict_types=1);

namespace App\Middleware\Messenger;

interface OrderMessageIdempotencyStoreInterface
{
    public function has(string $key): bool;

    public function put(string $key): void;
}
