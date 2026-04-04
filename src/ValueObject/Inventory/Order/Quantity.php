<?php

declare(strict_types=1);

namespace App\ValueObject\Inventory\Order;

final class Quantity
{
    public function __construct(private readonly int $value)
    {
        if ($this->value < 0) {
            throw new \InvalidArgumentException('Quantity cannot be negative.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
