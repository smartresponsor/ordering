<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ServiceInterface\Order\TaxationInterface;
use App\ValueObject\Order\Money;

final class Taxation implements TaxationInterface
{
    public function __construct(
        public readonly float $rate, // 0..1
        public readonly string $type, // 'vat'|'sales'|'none'
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
            return Money::zero($amount->currency);
        }
        $tax = bcmul($amount->amount, (string) $this->rate, 4);

        return new Money(number_format((float) $tax, 2, '.', ''), $amount->currency);
    }
}
