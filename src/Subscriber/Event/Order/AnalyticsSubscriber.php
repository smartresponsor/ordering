<?php

declare(strict_types=1);

namespace App\Subscriber\Event\Order;

use App\Entity\Order\OrderAnalyticsRecordEntity;
use App\Event\Domain\Order\OrderPaidEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class AnalyticsSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [OrderPaidEvent::class => 'onPaid'];
    }

    public function onPaid(OrderPaidEvent $e): void
    {
        $this->em->persist(new OrderAnalyticsRecordEntity('paid', $e->orderId));
        $this->em->flush();
    }
}
