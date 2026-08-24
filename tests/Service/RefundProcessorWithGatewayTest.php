<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Ordering\Contract\Gateway\Order\OrderPaymentGatewayInterface;
use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use App\Ordering\Entity\Order\OrderRefundTransactionEntity;
use App\Ordering\Event\Domain\Order\OrderRefundCompletedEvent;
use App\Ordering\Service\Outbox\OutboxWriter;
use App\Ordering\Service\Refund\Order\RefundProcessor;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class RefundProcessorWithGatewayTest extends TestCase
{
    public function testRefundCompleted(): void
    {
        $persisted = [];
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::exactly(2))
            ->method('persist')
            ->willReturnCallback(static function (object $entity) use (&$persisted): void {
                $persisted[] = $entity;
            });
        $em->expects(self::once())->method('flush');

        $gateway = $this->createMock(OrderPaymentGatewayInterface::class);
        $gateway->expects(self::once())->method('refund')->willReturn('gw_refund_1');
        $svc = new RefundProcessor($em, new OutboxWriter($em), $gateway);

        $tx = new OrderRefundTransactionEntity('TX1', 'ORD1', 'RET1', 'PAY1', 1500, 'USD');
        $svc->startRefund($tx);

        self::assertSame($tx, $persisted[0]);
        self::assertInstanceOf(OrderOutboxMessageEntity::class, $persisted[1]);
        self::assertSame(OrderRefundCompletedEvent::class, $persisted[1]->getEventType());
        $payload = $persisted[1]->payload();
        self::assertSame('ORD1', $payload['orderId']);
        self::assertSame('TX1', $payload['refundId']);
        self::assertSame('15.00', $payload['amount']);
        self::assertSame('USD', $payload['currency']);
        self::assertSame('gw_refund_1', $payload['externalRef']);
        self::assertNotSame('', $payload['occurredAt']);
        self::assertInstanceOf(\DateTimeImmutable::class, new \DateTimeImmutable($payload['occurredAt']));
    }
}
