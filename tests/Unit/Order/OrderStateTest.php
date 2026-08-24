<?php

declare(strict_types=1);

namespace Tests\Unit\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Event\Domain\Order\OrderCompletedEvent;
use App\Ordering\Event\Domain\Order\OrderRefundedEvent;
use App\Ordering\ValueObject\OrderStatus;
use PHPUnit\Framework\TestCase;

final class OrderStateTest extends TestCase
{
    public function testOrderTransitionsAcrossPaymentShipmentAndRefund(): void
    {
        $order = OrderEntity::create('USD', '120.00');
        $order->setStatus(OrderStatus::Placed);

        $payment = $order->applyPayment('120.00', 'pay-ref-1');
        self::assertSame('paid', $order->getStatus());

        $shipment = $order->ship('UPS', 'trk-1', 'packed');
        self::assertSame('shipped', $order->getStatus());

        $order->setStatus(OrderStatus::Returned);
        $refund = $order->refund('120.00', 'customer request');

        self::assertSame('stripe', $payment->getGateway());
        self::assertSame('UPS', $shipment->getCarrier());
        self::assertSame('120.00', $refund->getAmount());
        self::assertSame('refunded', $order->getStatus());
    }

    public function testCompletionEventIsScalarAndDurable(): void
    {
        $order = OrderEntity::create('USD', '50.00');
        $order->setVendorId('vendor-5');
        $order->setStatus(OrderStatus::Placed);
        $order->applyPayment('50.00', 'pay-ref-complete');
        $order->releaseEvents();
        $order->ship('UPS', 'trk-complete');
        $order->releaseEvents();

        $order->markAsCompleted();
        $events = $order->releaseEvents();

        self::assertSame('completed', $order->getStatus());
        self::assertCount(1, $events);
        self::assertInstanceOf(OrderCompletedEvent::class, $events[0]);
        self::assertSame($order->slug(), $events[0]->orderId);
        self::assertSame('vendor-5', $events[0]->vendorId);
        self::assertJson(json_encode(get_object_vars($events[0]), JSON_THROW_ON_ERROR));
    }

    public function testRefundEventIsScalarAndDurable(): void
    {
        $order = OrderEntity::create('USD', '50.00');
        $order->setVendorId('vendor-5');
        $order->setStatus(OrderStatus::Placed);
        $order->applyPayment('50.00', 'pay-ref-50');
        $order->releaseEvents();
        $order->ship('UPS', 'trk-refund-test');
        $order->releaseEvents();
        $order->setStatus(OrderStatus::Returned);

        $order->refund('20.00', 'partial refund');
        $events = $order->releaseEvents();

        self::assertCount(1, $events);
        self::assertInstanceOf(OrderRefundedEvent::class, $events[0]);
        self::assertSame($order->slug(), $events[0]->orderId);
        self::assertSame('20.00', $events[0]->amount);
        self::assertSame('USD', $events[0]->currency);
        self::assertSame('vendor-5', $events[0]->vendorId);
        self::assertJson(json_encode(get_object_vars($events[0]), JSON_THROW_ON_ERROR));
    }
}
