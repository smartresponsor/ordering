<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Ordering\Contract\Gateway\Order\OrderPaymentGatewayInterface;
use App\Ordering\Entity\Order\OrderRefundTransactionEntity;
use App\Ordering\Event\Domain\Order\OrderRefundCompletedEvent;
use App\Ordering\Service\Refund\Order\RefundProcessor;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class RefundProcessorWithGatewayTest extends TestCase
{
    public function testRefundCompleted(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->any())->method('persist');
        $em->expects($this->any())->method('flush');
        $bus = $this->createMock(MessageBusInterface::class);
        $bus->expects(self::once())
            ->method('dispatch')
            ->with(self::callback(static fn (object $event): bool => $event instanceof OrderRefundCompletedEvent
                && 'ORD1' === $event->orderId
                && 'TX1' === $event->refundId
                && '15.00' === $event->amount
                && 'USD' === $event->currency
                && 'gw_refund_1' === $event->externalRef))
            ->willReturn(new Envelope(new \stdClass()));

        $gateway = $this->createMock(OrderPaymentGatewayInterface::class);
        $gateway->expects($this->once())->method('refund')->willReturn('gw_refund_1');
        $svc = new RefundProcessor($em, $bus, $gateway);

        $tx = new OrderRefundTransactionEntity('TX1', 'ORD1', 'RET1', 'PAY1', 1500, 'USD');
        $svc->startRefund($tx);

        $this->addToAssertionCount(1);
    }
}
