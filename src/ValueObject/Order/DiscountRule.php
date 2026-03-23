<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

final class DiscountRule
{
    public function __construct(
        public readonly ?int $amountMinor = null,
        public readonly ?float $percent = null,
    ) {
    }

    public static function fixed(int $amountMinor): self
    {
        return new self($amountMinor, null);
    }

    public static function percent(float $percent): self
    {
        return new self(null, $percent);
    }
}
