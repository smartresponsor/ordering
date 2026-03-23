<?php

declare(strict_types=1);

namespace App\Message\Handler\Order;

use App\Entity\Order\Order;
use App\Message\Command\Order\OrderPartialPayCommand;
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
        $order = $this->em->getRepository(Order::class)->find($cmd->orderId);
        if (!$order) {
            throw new \RuntimeException('Order not found');
        }
        $order->applyPartialPayment($cmd->amount, $cmd->externalRef, true);

        foreach ($order->releaseEvents() as $ignored) {
            // тут пишем в outbox (упрощено — пропущено для краткости)
        }

        $this->em->flush();
    }
}
