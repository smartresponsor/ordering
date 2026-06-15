<?php

declare(strict_types=1);

namespace App\Message\Handler\Order;

use App\Entity\Order\OrderEntity;
use App\Message\Command\Order\OrderPartialShipCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderPartialShipHandler
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(OrderPartialShipCommand $cmd): void
    {
        /** @var \App\Repository\Order\OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($cmd->orderId);
        if (!$order instanceof OrderEntity) {
            throw new \RuntimeException('Order not found');
        }
        $order->shipItems($cmd->count, $cmd->note);

        foreach ($order->releaseEvents() as $ignored) {
            // outbox write (упрощённо)
        }

        $this->em->flush();
    }
}
