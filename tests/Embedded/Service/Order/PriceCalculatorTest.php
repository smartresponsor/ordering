<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Ordering\Service\Pricing\Order\CurrencyConversionService;
use App\Ordering\Service\Pricing\Order\DefaultPromotionStrategy;
use App\Ordering\Service\Pricing\Order\PriceCalculator;
use App\Ordering\Service\Pricing\Order\TaxationConfigLoader;
use App\Ordering\Service\Pricing\Order\VatExclusiveStrategy;
use App\Ordering\ValueObject\Pricing\Order\Currency;
use App\Ordering\ValueObject\Pricing\Order\Discount;
use App\Ordering\ValueObject\Pricing\Order\Money;
use App\Ordering\ValueObject\Pricing\Order\TaxRate;
use PHPUnit\Framework\TestCase;

final class PriceCalculatorTest extends TestCase
{
    public function testCalculateWithDiscountAndTax(): void
    {
        $subtotal = new Money('200.00', new Currency('USD'));
        $promos = new DefaultPromotionStrategy(Discount::percent('10')); // -10%
        $taxation = new VatExclusiveStrategy();
        $config = new TaxationConfigLoader(dirname(__DIR__, 4).'/config/taxation.yaml');
        $fx = new CurrencyConversionService(dirname(__DIR__, 4).'/config/exchange_rates.yaml');

        $calc = new PriceCalculator($promos, $taxation, $config, $fx);
        $rate = $config->rateFor('EU', 'DE'); // 19%
        $breakdown = $calc->calculate($subtotal, $rate, null);

        self::assertArrayHasKey('total', $breakdown);
        self::assertSame('34.20', $breakdown['tax']->getAmount());
    }

    public function testCalculateWithCurrencyConversion(): void
    {
        $subtotal = new Money('100.00', new Currency('USD'));
        $promos = new DefaultPromotionStrategy(null);
        $taxation = new VatExclusiveStrategy();
        $config = new TaxationConfigLoader(dirname(__DIR__, 4).'/config/taxation.yaml');
        $fx = new CurrencyConversionService(dirname(__DIR__, 4).'/config/exchange_rates.yaml');

        $calc = new PriceCalculator($promos, $taxation, $config, $fx);
        $rate = new TaxRate('20');
        $breakdown = $calc->calculate($subtotal, $rate, new Currency('EUR'));
        self::assertSame('EUR', $breakdown['total']->getCurrency()->getCode());
    }
}
