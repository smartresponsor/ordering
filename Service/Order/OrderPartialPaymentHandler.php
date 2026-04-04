<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order\IdempotencyKey;
use App\Entity\Order\OrderPartialPayment;
use App\Entity\Order\OutboxMessage;
use App\Message\Command\Order\OrderPartialPaymentCommand;
use App\ServiceInterface\Order\OrderPartialPaymentHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OrderPartialPaymentHandler implements OrderPartialPaymentHandlerInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(OrderPartialPaymentCommand $c): void
    {
        if ($c->idempotencyKey) {
            $found = $this->em->getRepository(IdempotencyKey::class)->find($c->idempotencyKey);
            if ($found) {
                return;
            }
            $this->em->persist(new IdempotencyKey($c->idempotencyKey));
        }

        $pp = new OrderPartialPayment(
            \Ramsey\Uuid\Uuid::uuid4()->toString(),
            $c->orderId,
            $c->amountMinor,
            $c->currency,
            $c->paymentMethod
        );
        $this->em->persist($pp);

        $evt = new OutboxMessage(
            \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'order.partial_paid',
            ['orderId' => $c->orderId, 'amountMinor' => $c->amountMinor, 'currency' => $c->currency]
        );
        $this->em->persist($evt);

        $this->em->flush();
    }
}
