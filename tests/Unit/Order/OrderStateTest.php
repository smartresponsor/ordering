<?php

declare(strict_types=1);

namespace Tests\Unit\Order;

use App\Entity\Order;
use PHPUnit\Framework\TestCase;

final class OrderStateTest extends TestCase
{
    public function testOrderTransitionsAcrossPaymentShipmentAndRefund(): void
    {
        $order = new Order('USD', '120.00');

        $payment = $order->applyPayment('120.00', 'pay-ref-1');
        self::assertSame('paid', $order->getStatus());

        $shipment = $order->ship('UPS', 'trk-1', 'packed');
        self::assertSame('shipped', $order->getStatus());

        $refund = $order->refund('120.00', 'customer request');

        self::assertSame('stripe', $payment->getGateway());
        self::assertSame('UPS', $shipment->getCarrier());
        self::assertSame('120.00', $refund->getAmount());
        self::assertSame('refunded', $order->getStatus());
    }
}
