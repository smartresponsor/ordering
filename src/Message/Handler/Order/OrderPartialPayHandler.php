<?php

declare(strict_types=1);

namespace App\Message\Handler\Order;

use App\Message\Command\Order\OrderPartialPayCommand;
use App\Ordering\Entity\Order\OrderEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderPartialPayHandler
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(OrderPartialPayCommand $cmd): void
    {
        /** @var \App\Ordering\Repository\Order\OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($cmd->orderId);
        if (!$order instanceof OrderEntity) {
            throw new \RuntimeException('Order not found');
        }
        $order->applyPartialPayment($cmd->amount, $cmd->externalRef);

        foreach ($order->releaseEvents() as $ignored) {
            // тут пишем в outbox (упрощено — пропущено для краткости)
        }

        $this->em->flush();
    }
}
