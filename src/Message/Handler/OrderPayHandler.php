<?php

declare(strict_types=1);

namespace App\Message\Handler;

use App\Entity\Order;
use App\Message\Command\OrderPayCommand;
use App\Service\Outbox\OutboxPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

#[AsMessageHandler(bus: 'messenger.bus.commands')]
final readonly class OrderPayHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private OutboxPublisher $outbox,
    ) {
    }

    /**
     * @throws \JsonException
     * @throws ExceptionInterface
     */
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
