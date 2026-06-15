<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Workflow\Order;

use App\Entity\Order\OrderRefundTransactionEntity;
use App\Event\Domain\Order\OrderRefundInitiatedEvent;
use App\Model\Order\OrderReturnRequest;
use App\Service\Refund\Order\RefundProcessor;
use App\ServiceInterface\Workflow\Order\ReturnWorkflowServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

final readonly class ReturnWorkflowService implements ReturnWorkflowServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private RefundProcessor $refund,
        private MessageBusInterface $bus,
    ) {
    }

    public function createReturnAndRefund(
        string $orderId,
        int $amountMinor,
        string $currency,
        ?string $reason = null,
    ): OrderReturnRequest {
        $return = new OrderReturnRequest(
            Uuid::v7()->toRfc4122(),
            $orderId,
            $amountMinor,
            $currency,
            $reason,
        );
        $return->approve();
        $this->em->persist($return);

        $tx = new OrderRefundTransactionEntity(
            Uuid::v7()->toRfc4122(),
            $orderId,
            $return->id(),
            'PAY-'.$orderId,
            $amountMinor,
            $currency,
        );
        $this->em->persist($tx);
        $this->em->flush();

        $this->bus->dispatch(new OrderRefundInitiatedEvent($orderId, $tx->id(), $amountMinor, $currency));

        // emulate async refund for now
        $this->refund->startRefund($tx);
        $return->markRefunded();
        $this->em->flush();

        return $return;
    }
}
