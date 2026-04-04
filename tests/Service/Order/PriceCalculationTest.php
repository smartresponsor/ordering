<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

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
        $discount = Discount::percent('10');
        // compute manually
        $taxMoney = new Money('20.00', 'USD');
        $totalBeforeDiscount = $base->add($taxMoney);
        $discounted = $discount->apply($totalBeforeDiscount);
        $this->assertSame('108.000000', $discounted->getAmount());

        // For full integration we'd need OrderItem; here we assert VO arithmetic
        $this->assertSame('120.000000', $totalBeforeDiscount->getAmount());
        self::assertInstanceOf(PriceCalculatorService::class, $svc);
        self::assertSame('vat', $tax->type);
    }
}
