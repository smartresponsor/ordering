<?php

declare(strict_types=1);

namespace App\Ordering\ValueObject\Inventory\Order;

final readonly class Sku
{
    public function __construct(private string $value)
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
