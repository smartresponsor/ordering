<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace Tests\Embedded\Service\Order;

use App\Ordering\Contract\Gateway\Order\OrderPaymentGatewayInterface;
use App\Ordering\Entity\Order\OrderRefundTransactionEntity;
use App\Ordering\Service\Outbox\OutboxWriter;
use App\Ordering\Service\Refund\Order\RefundProcessor;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class RefundProcessorWithGatewayTest extends TestCase
{
    public function testRefundCompleted(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::exactly(2))->method('persist');
        $em->expects(self::once())->method('flush');

        $gateway = $this->createMock(OrderPaymentGatewayInterface::class);
        $gateway->expects($this->once())->method('refund')->willReturn('gw_refund_1');
        $svc = new RefundProcessor($em, new OutboxWriter($em), $gateway);

        $tx = new OrderRefundTransactionEntity('TX1', 'ORD1', 'RET1', 'PAY1', 1500, 'USD');
        $svc->startRefund($tx);

        $this->addToAssertionCount(1);
    }
}
