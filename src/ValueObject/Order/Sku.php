<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

final readonly class Sku
{
    public function __construct(public string $value)
    {
        if ('' === $value) {
            throw new \InvalidArgumentException('Sku required');
        }
    }
}
