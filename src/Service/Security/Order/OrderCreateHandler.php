<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order;
use App\Message\Command\OrderCreateCommand;
use App\ServiceInterface\Security\Order\OrderCreateHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'messenger.bus.commands')]
final readonly class OrderCreateHandler implements OrderCreateHandlerInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {
    }

    public function __invoke(OrderCreateCommand $cmd): string
    {
        $order = Order::create($cmd->currency, $cmd->grandTotal);
        $this->em->persist($order);
        $this->em->flush();

        return $order->id();
    }
}
