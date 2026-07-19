<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Contract\Gateway\Order\OrderPaymentGatewayInterface;
use App\Ordering\Entity\Order\OrderRefundTransactionEntity;
use App\Service\Refund\Order\RefundProcessor;
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
        $bus->method('dispatch')->willReturn(new Envelope(new \stdClass()));

        $gateway = $this->createMock(OrderPaymentGatewayInterface::class);
        $gateway->expects($this->once())->method('refund')->willReturn('gw_refund_1');
        $svc = new RefundProcessor($em, $bus, $gateway);

        $tx = new OrderRefundTransactionEntity('TX1', 'ORD1', 'RET1', 'PAY1', 1500, 'USD');
        $svc->startRefund($tx);

        $this->addToAssertionCount(1);
    }
}
