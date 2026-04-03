<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace Tests\Embedded\Service\Order;

use App\Service\Pricing\Order\CurrencyConversionService;
use App\Service\Pricing\Order\InMemoryRateProvider;
use App\ValueObject\Order\Money;
use PHPUnit\Framework\TestCase;

final class MultiCurrencyTest extends TestCase
{
    public function testConvert(): void
    {
        $fx = new CurrencyConversionService(new InMemoryRateProvider(['USD:EUR' => 0.9]));
        $eur = $fx->convert(new Money('1000.00', 'USD'), 'EUR');
        $this->assertSame('EUR', $eur->getCurrency()->getCode());
        $this->assertSame('900.00', $eur->getAmount());
    }
}
