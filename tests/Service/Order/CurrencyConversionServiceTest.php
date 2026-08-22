<?php

declare(strict_types=1);

namespace Tests\Service\Order;

use App\Ordering\Service\Pricing\Order\CurrencyConversionService;
use App\Ordering\ValueObject\Pricing\Order\Currency;
use App\Ordering\ValueObject\Pricing\Order\Money;
use PHPUnit\Framework\TestCase;

final class CurrencyConversionServiceTest extends TestCase
{
    public function testConvertUsdToEur(): void
    {
        $fx = new CurrencyConversionService(__DIR__.'/../../../config/exchange_rates.yaml');
        $money = new Money('100.00', new Currency('USD'));
        $eur = $fx->convert($money, new Currency('EUR'), 2);
        $this->assertNotEmpty($eur->getAmount());
        $this->assertSame('EUR', (string) $eur->getCurrency());
    }
}
