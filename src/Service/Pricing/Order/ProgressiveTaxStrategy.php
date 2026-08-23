<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Pricing\Order;

use App\Ordering\ServiceInterface\Pricing\Order\ProgressiveTaxStrategyInterface;
use App\Ordering\ValueObject\Pricing\Order\Money;

/**
 * Progressive brackets in major units. Example:
 * [ [0, 100.00, 0.0], [100.00, 1000.00, 0.1], [1000.00, null, 0.2] ]
 */
final readonly class ProgressiveTaxStrategy implements ProgressiveTaxStrategyInterface
{
    /** @var array<int,array{float,float|null,float}> */
    private array $brackets;

    /** @param array<int,array{float,float|null,float}> $brackets */
    public function __construct(array $brackets)
    {
        foreach ($brackets as [$from, $to, $rate]) {
            if ($from < 0 || (null !== $to && $to < $from) || $rate < 0 || $rate > 1) {
                throw new \InvalidArgumentException('Invalid bracket');
            }
        }
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
            $segment = min($base, $upper) - $from;
            if ($segment > 0) {
                $tax += $segment * $rate;
            }
        }

        return new Money(number_format($tax, 2, '.', ''), $taxableBase->getCurrency());
    }
}
