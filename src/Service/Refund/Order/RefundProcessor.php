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
use App\Ordering\ServiceInterface\Refund\Order\RefundProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class RefundProcessor implements RefundProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageBusInterface $bus,
        private OrderPaymentGatewayInterface $gateway,
    ) {
    }

    public function startRefund(OrderRefundTransactionEntity $tx): void
    {
        $gatewayRef = $this->gateway->refund($tx->orderId(), $tx->amount(), ['refund_id' => $tx->refundId(), 'reason' => $tx->reason()]);
        if ('' === $gatewayRef) {
            return;
        }
        $this->em->persist($tx);
        $this->em->flush();

        $this->bus->dispatch(new OrderRefundCompletedEvent($tx->orderId(), $tx->id(), $tx->amount(), 'USD'));
    }
}
