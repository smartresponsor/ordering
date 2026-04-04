<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Entity\Order\OrderRefundTransaction;
use App\Integration\Payment\StripeStubGateway;
use App\Service\Refund\Order\RefundProcessor;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;

final class RefundProcessorWithGatewayTest extends TestCase
{
    public function testRefundCompleted(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('persist')->willReturn(null);
        $em->method('flush')->willReturn(null);
        $bus = $this->createMock(MessageBusInterface::class);
        $bus->method('dispatch')->willReturn(new class {});

        $gateway = new StripeStubGateway('test_secret');
        $svc = new RefundProcessor($em, $bus, $gateway);

        $tx = new OrderRefundTransaction('TX1', 'ORD1', 'RET1', 'PAY1', 1500, 'USD');
        $svc->startRefund($tx);

        $this->assertSame('completed', $tx->status());
    }
}
