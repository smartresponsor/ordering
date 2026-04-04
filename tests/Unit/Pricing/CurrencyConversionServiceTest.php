<?php

declare(strict_types=1);

namespace Tests\Unit\Pricing;

use App\Service\Pricing\Order\CurrencyConversionService;
use App\ValueObject\Pricing\Order\Currency;
use App\ValueObject\Pricing\Order\Money;
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
