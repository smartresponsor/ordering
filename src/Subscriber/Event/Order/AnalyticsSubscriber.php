<?php

declare(strict_types=1);

namespace App\Subscriber\Event\Order;

use App\Entity\Analytics\AnalyticsRecord;
use App\Event\Domain\Order\OrderPaidEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class AnalyticsSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {
    }

    public static function getSubscribedEvents(): array
    {
        return [OrderPaidEvent::class => 'onPaid'];
    }

    public function onPaid(OrderPaidEvent $e): void
    {
        $this->em->persist(new AnalyticsRecord('paid', (string) $e->orderId));
        $this->em->flush();
    }
}
