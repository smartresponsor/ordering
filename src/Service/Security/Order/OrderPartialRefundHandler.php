<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order;
use App\Message\Command\Order\OrderPartialRefundCommand;
use App\ServiceInterface\Security\Order\OrderPartialRefundHandlerInterface;
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
        $order = $this->em->getRepository(Order::class)->find($cmd->orderId);
        if (!$order) {
            throw new \RuntimeException('Order not found');
        }
        $order->refundPartial($cmd->amount, $cmd->reason, true);

        foreach ($order->releaseEvents() as $ignored) {
            // outbox write (упрощённо)
        }

        $this->em->flush();
    }
}
