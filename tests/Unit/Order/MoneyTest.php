<?php

declare(strict_types=1);

namespace Tests\Unit\Order;

use App\ValueObject\Pricing\Order\Money;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function testAddSub(): void
    {
        $a = new Money('10.00', 'USD');
        $b = new Money('2.50', 'USD');
        self::assertSame('12.500000', $a->add($b)->getAmount());
        self::assertSame('7.500000', $a->sub($b)->getAmount());
    }
}
