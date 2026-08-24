<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Refund\Order;

use App\Ordering\Contract\Gateway\Order\OrderPaymentGatewayInterface;
use App\Ordering\Entity\Order\OrderRefundTransactionEntity;
use App\Ordering\Event\Domain\Order\OrderRefundCompletedEvent;
use App\Ordering\Service\Outbox\OutboxWriter;
use App\Ordering\ServiceInterface\Refund\Order\RefundProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class RefundProcessor implements RefundProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OutboxWriter $outbox,
        private OrderPaymentGatewayInterface $gateway,
    ) {
    }

    public function startRefund(OrderRefundTransactionEntity $tx): void
    {
        $gatewayRef = $this->gateway->refund($tx->orderId(), $tx->amount(), ['refund_id' => $tx->refundId(), 'reason' => $tx->reason()]);
        if ('' === $gatewayRef) {
            return;
        }
        $event = new OrderRefundCompletedEvent(
            $tx->orderId(),
            $tx->id(),
            $tx->amount(),
            $tx->currency(),
            $gatewayRef,
            (new \DateTimeImmutable())->format(DATE_ATOM),
        );

        $this->em->persist($tx);
        $this->outbox->store(OrderRefundCompletedEvent::class, get_object_vars($event));
        $this->em->flush();
    }
}
