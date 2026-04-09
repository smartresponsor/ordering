<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order;
use App\Message\Command\Order\OrderPartialShipCommand;
use App\ServiceInterface\Security\Order\OrderPartialShipHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderPartialShipHandler implements OrderPartialShipHandlerInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {
    }

    public function __invoke(OrderPartialShipCommand $cmd): void
    {
        $order = $this->em->getRepository(Order::class)->find($cmd->orderId);
        if (!$order) {
            throw new \RuntimeException('Order not found');
        }
        $order->shipItems($cmd->count, $cmd->note);

        foreach ($order->releaseEvents() as $event) {
            // outbox write (упрощённо)
        }

        $this->em->flush();
    }
}
