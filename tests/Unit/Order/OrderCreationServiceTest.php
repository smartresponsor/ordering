<?php

declare(strict_types=1);

namespace App\Ordering\Tests\Unit\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderPaymentEntity;
use App\Ordering\Entity\Order\OrderShipmentEntity;
use App\Ordering\Event\Domain\Order\OrderPaidEvent;
use App\Ordering\Event\Domain\Order\OrderPlacedEvent;
use App\Ordering\Event\Domain\Order\OrderShippedEvent;
use App\Ordering\Service\Order\OrderCreationService;
use App\Ordering\Service\Payment\PaymentProcessorService;
use App\Ordering\Service\Shipment\ShipmentProcessorService;
use App\Ordering\ServiceInterface\Payment\PaymentGatewayInterface;
use App\Ordering\ServiceInterface\Shipment\CarrierInterface;
use App\Ordering\ValueObject\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class OrderCreationServiceTest extends TestCase
{
    public function testCreatesAndPersistsPlacedOrderForCustomerAndVendor(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())
            ->method('persist')
            ->with(self::callback(static fn (mixed $value): bool => $value instanceof OrderEntity));
        $entityManager->expects(self::once())->method('flush');

        $order = (new OrderCreationService($entityManager))->create(' customer-1 ', ' vendor-2 ', 'usd', '125.50');

        self::assertSame('customer-1', $order->getCustomerId());
        self::assertSame('vendor-2', $order->getVendorId());
        self::assertSame('USD', $order->getCurrency());
        self::assertSame('125.50', $order->getGrandTotal());
        self::assertSame('placed', $order->getStatus());

        $events = $order->releaseEvents();
        self::assertCount(1, $events);
        self::assertInstanceOf(OrderPlacedEvent::class, $events[0]);
        self::assertSame($order->slug(), $events[0]->orderId);
    }

    public function testChargePreservesPaymentFactsAndUpdatesOrder(): void
    {
        $order = OrderEntity::create('USD', '100.00');
        $order->setStatus(OrderStatus::Placed);

        $gateway = $this->createMock(PaymentGatewayInterface::class);
        $gateway->expects(self::once())
            ->method('charge')
            ->with($order, 10000)
            ->willReturn('pay-ref-100');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::exactly(2))
            ->method('persist')
            ->with(self::logicalOr(
                self::isInstanceOf(OrderPaymentEntity::class),
                self::identicalTo($order),
            ));

        $payment = (new PaymentProcessorService($gateway, $entityManager))->charge($order, 10000);

        self::assertSame('100.00', $payment->getAmount());
        self::assertSame('USD', $payment->getCurrency());
        self::assertSame('pay-ref-100', $payment->getReference());
        self::assertSame('100.00', $order->getPaidTotal());
        self::assertSame('paid', $order->getStatus());

        $events = $order->releaseEvents();
        self::assertCount(1, $events);
        self::assertInstanceOf(OrderPaidEvent::class, $events[0]);
        self::assertSame($order->slug(), $events[0]->orderId);
        self::assertSame('100.00', $events[0]->amount);
        self::assertSame('USD', $events[0]->currency);
        self::assertSame('pay-ref-100', $events[0]->externalRef);
    }

    public function testShipPreservesCarrierTrackingAndOrderEvent(): void
    {
        $order = OrderEntity::create('USD', '100.00');
        $order->setStatus(OrderStatus::Placed);
        $order->applyPayment('100.00', 'pay-ref-ship');
        $order->releaseEvents();

        $carrier = $this->createMock(CarrierInterface::class);
        $carrier->expects(self::once())
            ->method('createShipment')
            ->with('UPS', $order->slug())
            ->willReturn('1ZTRACK100');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::exactly(2))
            ->method('persist')
            ->with(self::logicalOr(
                self::isInstanceOf(OrderShipmentEntity::class),
                self::identicalTo($order),
            ));

        $shipment = (new ShipmentProcessorService($carrier, $entityManager))->ship($order);

        self::assertSame('UPS', $shipment->getCarrier());
        self::assertSame('1ZTRACK100', $shipment->getTrackingCode());
        self::assertSame('1ZTRACK100', $order->getTrackingCode());
        self::assertSame('shipped', $order->getStatus());

        $events = $order->releaseEvents();
        self::assertCount(1, $events);
        self::assertInstanceOf(OrderShippedEvent::class, $events[0]);
        self::assertSame($order->slug(), $events[0]->orderId);
        self::assertSame('UPS', $events[0]->carrier);
        self::assertSame('1ZTRACK100', $events[0]->trackingCode);
    }

    public function testRejectsMissingCustomerIdentifier(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::never())->method('persist');
        $entityManager->expects(self::never())->method('flush');

        $this->expectException(\InvalidArgumentException::class);
        (new OrderCreationService($entityManager))->create(' ', 'vendor-2', 'USD', '10.00');
    }
}
