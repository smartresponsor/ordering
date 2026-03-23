<?php

declare(strict_types=1);

namespace App\Service\Order\Taxation;

use App\ValueObject\Order\Money;

final class FlatTaxStrategy implements TaxationStrategyInterface
{
    public function __construct(private readonly float $rate)
    {
    }

    public function tax(Money $taxableBase): Money
    {
        $amount = (int) round((float) $taxableBase->getAmount() * 100 * $this->rate, 0);

        return new Money(number_format($amount / 100, 2, '.', ''), $taxableBase->getCurrency());
    }
}
