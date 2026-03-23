<?php

declare(strict_types=1);

namespace App\ValueObject;

final readonly class ReservationStatus
{
    private function __construct(
        public bool $success,
        public ?string $reason = null,
    ) {
    }

    public static function success(): self
    {
        return new self(true, null);
    }

    public static function failed(string $reason): self
    {
        return new self(false, $reason);
    }
}
