<?php

declare(strict_types=1);

namespace App\ValueObject\Pricing\Order;

final readonly class Sku
{
    public function __construct(private string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
