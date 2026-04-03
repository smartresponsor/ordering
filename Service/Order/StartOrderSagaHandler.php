<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order;
use App\Message\Command\StartOrderSagaCommand;
use App\Saga\OrderSaga;
use App\ServiceInterface\Order\StartOrderSagaHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
final class StartOrderSagaHandler implements StartOrderSagaHandlerInterface
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly OrderSaga $saga)
    {
    }

    public function __invoke(StartOrderSagaCommand $cmd): void
    {
        $order = $this->em->find(Order::class, $cmd->orderId);
        if (!$order) {
            return;
        }

        $this->saga->execute($order);
    }
}
