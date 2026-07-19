<?php

declare(strict_types=1);

namespace App\Message\Handler;

use App\Message\Command\OrderCreateCommand;
use App\Ordering\Entity\Order\OrderEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'messenger.bus.default')]
final readonly class OrderCreateHandler
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(OrderCreateCommand $cmd): string
    {
        $order = OrderEntity::create($cmd->currency, $cmd->grandTotal);
        $this->em->persist($order);
        $this->em->flush();

        return $order->slug();
    }
}
