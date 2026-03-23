<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

final readonly class Taxation
{
    public function __construct(public float $rate, public ?string $kind = null)
    {
        if ($rate < 0 || $rate > 1) {
            throw new \InvalidArgumentException('0..1');
        }
    }
}
