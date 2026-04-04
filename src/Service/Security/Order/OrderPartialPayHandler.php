<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order;
use App\Message\Command\Order\OrderPartialPayCommand;
use App\ServiceInterface\Security\Order\OrderPartialPayHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OrderPartialPayHandler implements OrderPartialPayHandlerInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(OrderPartialPayCommand $cmd): void
    {
        $order = $this->em->getRepository(Order::class)->find($cmd->orderId);
        if (!$order) {
            throw new \RuntimeException('Order not found');
        }
        $order->applyPartialPayment($cmd->amount, $cmd->externalRef, true);

        foreach ($order->releaseEvents() as $event) {
            // тут пишем в outbox (упрощено — пропущено для краткости)
        }

        $this->em->flush();
    }
}
