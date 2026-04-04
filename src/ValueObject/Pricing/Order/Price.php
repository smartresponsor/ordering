<?php

declare(strict_types=1);

namespace App\ValueObject\Pricing\Order;

use App\ValueObject\Pricing\Order\Money;

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
        Money::assertSameCurrency($base, $tax);
        Money::assertSameCurrency($base, $discount);
        $total = $base->add($tax)->sub($discount);

        return new self($base, $tax, $discount, $total);
    }
}
