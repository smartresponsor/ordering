<?php

declare(strict_types=1);

namespace App\ValueObject\Pricing\Order;

final readonly class Quantity
{
    public function __construct(private int $value)
    {
    }

    public function toInt(): int
    {
        return max(1, $this->value);
    }
}
