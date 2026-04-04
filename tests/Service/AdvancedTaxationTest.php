<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Service\Order\Taxation\FlatTaxStrategy;
use App\Service\Order\Taxation\ProgressiveTaxStrategy;
use App\ValueObject\Pricing\Order\Money;
use PHPUnit\Framework\TestCase;

final class AdvancedTaxationTest extends TestCase
{
    public function testFlatTax(): void
    {
        $tax = new FlatTaxStrategy(0.2);
        $t = $tax->tax(new Money(10000, 'USD'));
        $this->assertSame(2000, $t->amountMinor());
    }

    public function testProgressive(): void
    {
        $br = [
            [0, 10000, 0.0],
            [10001, 50000, 0.1],
            [50001, null, 0.2],
        ];
        $tax = new ProgressiveTaxStrategy($br);
        $t = $tax->tax(new Money(70000, 'USD'));
        $this->assertTrue($t->amountMinor() > 0);
    }
}
