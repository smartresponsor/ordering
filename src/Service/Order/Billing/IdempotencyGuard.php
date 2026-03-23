<?php

declare(strict_types=1);

namespace App\Service\Order\Billing;

use App\Service\Order\IdempotencyGuard as BaseIdempotencyGuard;

final readonly class IdempotencyGuard
{
    public function __construct(private BaseIdempotencyGuard $guard)
    {
    }

    public function checkAndPersist(string $provider, string $eventId, string $payload): bool
    {
        return $this->guard->checkAndPersist($provider, $eventId, $payload);
    }
}
