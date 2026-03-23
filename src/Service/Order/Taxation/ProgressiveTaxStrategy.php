<?php

declare(strict_types=1);

namespace App\Service\Order\Taxation;

use App\ValueObject\Order\Money;

final class ProgressiveTaxStrategy implements TaxationStrategyInterface
{
    /** @var array<int,array{0:float,1:float|null,2:float}> */
    private array $brackets;

    /** @param array<int,array{0:float,1:float|null,2:float}> $brackets */
    public function __construct(array $brackets)
    {
        $this->brackets = $brackets;
    }

    public function tax(Money $taxableBase): Money
    {
        $base = (float) $taxableBase->getAmount();
        $tax = 0.0;

        foreach ($this->brackets as [$from, $to, $rate]) {
            if ($base <= $from) {
                continue;
            }

            $upper = $to ?? $base;
            $segment = max(0.0, min($base, $upper) - $from);
            $tax += $segment * $rate;
        }

        return new Money(number_format($tax, 2, '.', ''), $taxableBase->getCurrency());
    }
}
