<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\Order;
use App\Message\Command\RetryOrderSagaCommand;
use App\Saga\OrderSaga;
use App\ServiceInterface\Order\RetryOrderSagaHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
final class RetryOrderSagaHandler implements RetryOrderSagaHandlerInterface
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly OrderSaga $saga)
    {
    }

    public function __invoke(RetryOrderSagaCommand $cmd): void
    {
        $order = $this->em->find(Order::class, $cmd->orderId);
        if (!$order) {
            return;
        }

        $this->saga->execute($order);
    }
}
