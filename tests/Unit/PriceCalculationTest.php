<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Service\Pricing\Order\PriceCalculatorService;
use App\Service\Pricing\Order\VatExclusiveStrategy;
use App\ValueObject\Pricing\Order\Discount;
use App\ValueObject\Pricing\Order\Money;
use App\ValueObject\Pricing\Order\Taxation;
use PHPUnit\Framework\TestCase;

final class PriceCalculationTest extends TestCase
{
    public function testPriceCalculationVatExclusiveWithPercentDiscount(): void
    {
        $tax = new Taxation(0.20, 'vat');
        $strategy = new VatExclusiveStrategy();
        $svc = new PriceCalculatorService($strategy);

        $base = new Money('100.00', 'USD');
        $discount = new Discount(0.10, null); // 10%
        // compute manually
        $taxMoney = new Money('20.00', 'USD');
        $totalBeforeDiscount = $base->add($taxMoney);
        $discounted = $discount->apply($totalBeforeDiscount);
        $this->assertSame('108.00', $discounted->amount);

        // For full integration we'd need OrderItem; here we assert VO arithmetic
        $this->assertSame('120.00', $totalBeforeDiscount->amount);
    }
}
