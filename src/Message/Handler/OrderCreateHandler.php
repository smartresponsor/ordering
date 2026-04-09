<?php

declare(strict_types=1);

namespace App\Message\Handler;

use App\Entity\Order;
use App\Message\Command\OrderCreateCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'messenger.bus.commands')]
final readonly class OrderCreateHandler
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
