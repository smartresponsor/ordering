<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Workflow\Order;

use App\Message\Command\RetryOrderSagaCommand;
use App\Ordering\Entity\Order\OrderEntity;
use App\Saga\OrderSaga;
use App\ServiceInterface\Workflow\Order\RetryOrderSagaHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
final readonly class RetryOrderSagaHandler implements RetryOrderSagaHandlerInterface
{
    public function __construct(private EntityManagerInterface $em, private OrderSaga $saga)
    {
    }

    public function __invoke(RetryOrderSagaCommand $cmd): void
    {
        /** @var \App\Ordering\Repository\Order\OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($cmd->orderId);
        if (!$order instanceof OrderEntity) {
            return;
        }

        $this->saga->execute($order);
    }
}
