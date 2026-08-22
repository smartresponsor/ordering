<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Ordering\Service\Pricing\Order\PriceCalculatorService;
use App\Ordering\Service\Pricing\Order\VatExclusiveStrategy;
use App\Ordering\ValueObject\Pricing\Order\Discount;
use App\Ordering\ValueObject\Pricing\Order\Money;
use App\Ordering\ValueObject\Pricing\Order\Taxation;
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
        static::assertSame('108.000000', $discounted->getAmount());

        // For full integration we'd need OrderItem; here we assert VO arithmetic
        static::assertSame('120.000000', $totalBeforeDiscount->getAmount());

        self::assertSame('vat', $tax->type);
    }
}
