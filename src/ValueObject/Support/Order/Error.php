<?php

declare(strict_types=1);

namespace App\ValueObject\Support\Order;

final readonly class Error
{
    public function __construct(public string $code, public string $message)
    {
    }

    public static function fromArray(array $a): self
    {
        return new self($a['code'] ?? 'unknown', $a['message'] ?? '');
    }
}
