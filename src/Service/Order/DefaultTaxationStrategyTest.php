<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Service\Order\Pricing\DefaultTaxationStrategy;
use App\ValueObject\Order\Currency;
use App\ValueObject\Order\Money;
use App\ValueObject\Order\TaxRate;
use PHPUnit\Framework\TestCase;

final class DefaultTaxationStrategyTest extends TestCase
{
    public function testTaxCalculation(): void
    {
        $taxation = new DefaultTaxationStrategy();
        $base = new Money('100.00', new Currency('USD'));
        $tax = $taxation->tax($base, new TaxRate('20'));
        $this->assertSame('20.000000', $tax->getAmount());
    }
}
