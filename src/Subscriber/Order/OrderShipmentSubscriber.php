<?php

declare(strict_types=1);

namespace App\Subscriber\Order;

use App\Event\Order\OrderDeliveredEvent;
use App\Event\Order\OrderReturnWindowExpiredEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OrderShipmentSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderDeliveredEvent::class => 'onDelivered',
            OrderReturnWindowExpiredEvent::class => 'onReturnExpired',
        ];
    }

    public function onDelivered(OrderDeliveredEvent $event): void
    {
        $this->logger->info('Order delivered', [
            'shipmentId' => $event->shipment->getId(),
        ]);
    }

    public function onReturnExpired(OrderReturnWindowExpiredEvent $event): void
    {
        $this->logger->info('Return window expired', [
            'expiresAt' => $event->policy->getAutoExpireDate()?->format(\DateTimeInterface::ATOM),
        ]);
    }
}
