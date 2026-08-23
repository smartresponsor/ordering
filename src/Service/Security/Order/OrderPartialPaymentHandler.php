<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Security\Order;

use App\Model\Order\OrderPartialPayment;
use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use App\Ordering\Message\Command\Order\OrderPartialPaymentCommand;
use App\Ordering\ServiceInterface\Security\Order\OrderPartialPaymentHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderPartialPaymentHandler implements OrderPartialPaymentHandlerInterface
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
            Uuid::v7()->toRfc4122(),
            $c->orderId,
            $c->amountMinor,
            $c->currency,
            $c->paymentMethod
        );
        $this->em->persist($pp);

        $evt = new OrderOutboxMessageEntity(
            Uuid::v7()->toRfc4122(),
            'order.partial_paid',
            ['orderId' => $c->orderId, 'amountMinor' => $c->amountMinor, 'currency' => $c->currency]
        );
        $this->em->persist($evt);

        $this->em->flush();
    }
}
