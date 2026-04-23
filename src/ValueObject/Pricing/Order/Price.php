<?php

declare(strict_types=1);

namespace App\ValueObject\Pricing\Order;

final readonly class Price
{
    public function __construct(
        public Money $base,
        public Money $tax,
        public Money $discount,
        public Money $total,
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
