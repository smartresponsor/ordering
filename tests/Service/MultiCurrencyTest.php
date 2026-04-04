<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Service\Pricing\Order\CurrencyConversionService;
use App\Service\Pricing\Order\InMemoryRateProvider;
use App\ValueObject\Pricing\Order\Money;
use PHPUnit\Framework\TestCase;

final class MultiCurrencyTest extends TestCase
{
    public function testConvert(): void
    {
        $fx = new CurrencyConversionService(new InMemoryRateProvider(['USD:EUR' => 0.9]));
        $eur = $fx->convert(new Money(1000, 'USD'), 'EUR');
        $this->assertSame('EUR', $eur->currency());
        $this->assertSame(900, $eur->amountMinor());
    }
}
