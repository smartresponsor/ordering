<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

final class Price
{
    public function __construct(
        public readonly Money $base,
        public readonly Money $tax,
        public readonly Money $discount,
        public readonly Money $total,
    ) {
    }

    public static function fromParts(Money $base, Money $tax, Money $discount): self
    {
        $total = $base->add($tax)->subtract($discount);

        return new self($base, $tax, $discount, $total);
    }
}
