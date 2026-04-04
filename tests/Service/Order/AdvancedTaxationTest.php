<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace Tests\Service\Order;

use App\Service\Pricing\Order\FlatTaxStrategy;
use App\Service\Pricing\Order\ProgressiveTaxStrategy;
use App\ValueObject\Pricing\Order\Money;
use PHPUnit\Framework\TestCase;

final class AdvancedTaxationTest extends TestCase
{
    public function testFlatTax(): void
    {
        $tax = new FlatTaxStrategy(0.2);
        $t = $tax->tax(new Money('10000.00', 'USD'));
        $this->assertSame('2000.00', $t->getAmount());
    }

    public function testProgressive(): void
    {
        $br = [
            [0, 10000, 0.0],
            [10001, 50000, 0.1],
            [50001, null, 0.2],
        ];
        $tax = new ProgressiveTaxStrategy($br);
        $t = $tax->tax(new Money('70000.00', 'USD'));
        $this->assertGreaterThan(0.0, (float) $t->getAmount());
    }
}
