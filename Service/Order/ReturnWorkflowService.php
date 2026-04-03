<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\OrderRefundTransaction;
use App\Entity\Order\OrderReturnRequest;
use App\Event\Domain\Order\OrderRefundInitiatedEvent;
use App\ServiceInterface\Order\ReturnWorkflowServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;

final class ReturnWorkflowService implements ReturnWorkflowServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private RefundProcessor $refund,
        private MessageBusInterface $bus,
    ) {
    }

    public function createReturnAndRefund(string $orderId, int $amountMinor, string $currency, ?string $reason = null): OrderReturnRequest
    {
        $return = new OrderReturnRequest(Uuid::uuid4()->toString(), $orderId, $amountMinor, $currency, $reason);
        $return->approve();
        $this->em->persist($return);

        $tx = new OrderRefundTransaction(Uuid::uuid4()->toString(), $orderId, $return->id(), 'PAY-'.$orderId, $amountMinor, $currency);
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
