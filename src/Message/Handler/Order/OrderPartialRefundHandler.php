<?php

declare(strict_types=1);

namespace App\Message\Handler\Order;

use App\Entity\Order;
use App\Message\Command\Order\OrderPartialRefundCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderPartialRefundHandler
{
    public function __construct(private readonly EntityManagerInterface $em) {
    }

    public function __invoke(OrderPartialRefundCommand $cmd): void
    {
        $order = $this->em->getRepository(Order::class)->find($cmd->orderId);
        if (!$order) {
            throw new \RuntimeException('Order not found');
        }
        $order->refundPartial($cmd->amount, $cmd->reason, true);

        foreach ($order->releaseEvents() as $ignored) {
            // outbox write (упрощённо)
        }

        $this->em->flush();
    }
}
