<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order;
use App\Message\Command\OrderPayCommand;
use App\Service\Outbox\OutboxPublisher;
use App\ServiceInterface\Security\Order\OrderPayHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'messenger.bus.default')]
final readonly class OrderPayHandler implements OrderPayHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OutboxPublisher $outbox,
    ) {
    }

    public function __invoke(OrderPayCommand $cmd): void
    {
        /** @var Order|null $order */
        $order = $this->em->getRepository(Order::class)->findOneBy(['id' => $cmd->orderId]);
        if (!$order) {
            throw new \RuntimeException('Order not found: '.$cmd->orderId);
        }

        $order->applyPartialPayment($cmd->amount, $cmd->externalRef);
        $this->em->flush();

        foreach ($order->releaseEvents() as $event) {
            $this->outbox->storeAndPublish(
                $order->id(),
                $event::class,
                get_object_vars($event)
            );
        }
        $this->em->flush();
    }
}
