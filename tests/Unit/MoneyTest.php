<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Ordering\ValueObject\Pricing\Order\Money;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function testAddSub(): void
    {
        $a = new Money('10.00', 'USD');
        $b = new Money('2.50', 'USD');
        self::assertSame('12.50', $a->add($b)->amount);
        self::assertSame('7.50', $a->sub($b)->amount);
    }
}
