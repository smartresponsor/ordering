<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Entity\Order\OrderEntity;
use PHPUnit\Framework\TestCase;

final class PartialPaymentTest extends TestCase
{
    public function testPartialThenFullPaymentTransitionsStatus(): void
    {
        $order = new OrderEntity('USD', '100.00');
        $order->applyPartialPayment('30.00', 'ref-1', true);
        $this->assertSame('partially_paid', $order->status());
        $order->applyPartialPayment('70.00', 'ref-2', true);
        $this->assertSame('paid', $order->status());
        $this->assertSame('100.00', $order->paidTotal());
    }
}
