<?php

declare(strict_types=1);

namespace App\ValueObject\Inventory\Order;

final class Sku
{
    public function __construct(private readonly string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
