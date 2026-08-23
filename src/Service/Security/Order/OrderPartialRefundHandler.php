<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Security\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Message\Command\Order\OrderPartialRefundCommand;
use App\Ordering\ServiceInterface\Security\Order\OrderPartialRefundHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderPartialRefundHandler implements OrderPartialRefundHandlerInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(OrderPartialRefundCommand $cmd): void
    {
        /** @var \App\Ordering\Repository\Order\OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($cmd->orderId);
        if (!$order instanceof OrderEntity) {
            throw new \RuntimeException('Order not found');
        }
        $order->refundPartial($cmd->amount, $cmd->reason, true);

        foreach ($order->releaseEvents() as $ignored) {
            // outbox write (СѓРїСЂРѕС‰С‘РЅРЅРѕ)
        }

        $this->em->flush();
    }
}
