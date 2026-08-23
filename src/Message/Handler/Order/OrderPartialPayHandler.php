<?php

declare(strict_types=1);

namespace App\Ordering\Message\Handler\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Message\Command\Order\OrderPartialPayCommand;
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
            // С‚СѓС‚ РїРёС€РµРј РІ outbox (СѓРїСЂРѕС‰РµРЅРѕ вЂ” РїСЂРѕРїСѓС‰РµРЅРѕ РґР»СЏ РєСЂР°С‚РєРѕСЃС‚Рё)
        }

        $this->em->flush();
    }
}
