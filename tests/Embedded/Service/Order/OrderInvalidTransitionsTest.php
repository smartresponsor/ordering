<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Entity\Order\OrderEntity;
use PHPUnit\Framework\TestCase;

final class OrderInvalidTransitionsTest extends TestCase
{
    public function testOverpayThrows(): void
    {
        $o = new OrderEntity('USD', '100.00');
        $o->applyPartialPayment('80.00', 'r1', true);
        $this->expectException(\DomainException::class);
        $o->applyPartialPayment('21.00', 'r2', true);
    }

    public function testRefundBeforePayThrows(): void
    {
        $o = new OrderEntity('USD', '100.00');
        $this->expectException(\DomainException::class);
        $o->refundPartial('10.00', 'no pay yet');
    }

    public function testShipBeforePaidThrows(): void
    {
        $o = new OrderEntity('USD', '100.00');
        $o->applyPartialPayment('50.00', 'r1', true);
        $this->assertSame('partially_paid', $o->status());
        $this->expectException(\DomainException::class);
        $o->shipItems(1, 'too early');
    }
}
