<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order\IdempotencyKey;
use App\Entity\Order\OrderRefund;
use App\Entity\Order\OutboxMessage;
use App\Message\Command\Order\OrderRefundCommand;
use App\ServiceInterface\Security\Order\OrderRefundHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OrderRefundHandler implements OrderRefundHandlerInterface
{
    public function __construct(private EntityManagerInterface $em, private RefundPolicyService $policy)
    {
    }

    public function __invoke(OrderRefundCommand $c): void
    {
        if ($c->idempotencyKey) {
            $found = $this->em->getRepository(IdempotencyKey::class)->find($c->idempotencyKey);
            if ($found) {
                return;
            }
            $this->em->persist(new IdempotencyKey($c->idempotencyKey));
        }

        $refund = new OrderRefund(
            \Ramsey\Uuid\Uuid::uuid4()->toString(),
            $c->orderId,
            $c->amountMinor,
            $c->currency,
            $c->reason,
            $c->paymentRef
        );
        $this->em->persist($refund);

        $evt = new OutboxMessage(
            \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'order.refunded',
            ['orderId' => $c->orderId, 'amountMinor' => $c->amountMinor, 'currency' => $c->currency, 'reason' => $c->reason]
        );
        $this->em->persist($evt);

        $this->em->flush();
    }
}
