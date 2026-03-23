<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\ProgressiveTaxStrategyInterface;
use App\ValueObject\Order\Money;

/**
 * Progressive brackets in minor units. Example:
 * [ [0, 10000, 0.0], [10001, 100000, 0.1], [100001, null, 0.2] ]
 */
final class ProgressiveTaxStrategy implements ProgressiveTaxStrategyInterface
{
    /** @var array<int,array{int,int|null,float}> */
    private array $brackets;

    /** @param array<int,array{int,int|null,float}> $brackets */
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
        $remain = $taxableBase->amountMinor();
        $tax = 0;
        $cursor = 0;
        foreach ($this->brackets as [$from, $to, $rate]) {
            if ($remain <= 0) {
                break;
            }
            $lower = max($from, $cursor);
            $upper = $to ?? $remain + $lower;
            if ($remain + $cursor < $lower) {
                continue;
            }
            $segment = max(0, min($remain + $cursor, $upper) - $lower + 1);
            // Convert to segment amount within remain
            $segAmount = min($remain, $segment);
            $tax += (int) round($segAmount * $rate, 0);
            $remain -= $segAmount;
            $cursor = $upper + 1;
        }

        return new Money($tax, $taxableBase->currency());
    }
}
