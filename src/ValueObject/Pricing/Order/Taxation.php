<?php

declare(strict_types=1);

namespace App\ValueObject\Pricing\Order;

final class Taxation
{
    public function __construct(
        public readonly float $rate,
        public readonly string $type,
    ) {
        if ($this->rate < 0 || $this->rate > 1) {
            throw new \InvalidArgumentException('Invalid tax rate');
        }
        if (!in_array($this->type, ['vat', 'sales', 'none'], true)) {
            throw new \InvalidArgumentException('Invalid tax type');
        }
    }

    public function calculateTax(Money $amount): Money
    {
        if ('none' === $this->type || 0.0 === $this->rate) {
            return Money::zero($amount->getCurrency());
        }

        $tax = bcmul($amount->getAmount(), (string) $this->rate, 4);

        return new Money(number_format((float) $tax, 2, '.', ''), $amount->getCurrency());
    }
}
