<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order;
use App\Entity\Order\OrderRefund;
use App\Entity\Outbox\IdempotencyKey;
use App\Entity\Outbox\OutboxMessage;
use App\Message\Command\Order\OrderRefundCommand;
use App\Service\Refund\Order\RefundPolicyService;
use App\ServiceInterface\Security\Order\OrderRefundHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final readonly class OrderRefundHandler implements OrderRefundHandlerInterface
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

        $order = $this->em->find(Order::class, $c->orderId);
        if (!$order instanceof Order) {
            return;
        }

        $refund = new OrderRefund(
            $order,
            number_format($c->amountMinor / 100, 2, '.', ''),
            $c->currency,
            $c->reason,
            true,
        );
        $this->em->persist($refund);

        $evt = new OutboxMessage(
            Uuid::v7()->toRfc4122(),
            'order.refunded',
            ['orderId' => $c->orderId, 'amountMinor' => $c->amountMinor, 'currency' => $c->currency, 'reason' => $c->reason]
        );
        $this->em->persist($evt);

        $this->em->flush();
    }
}
