<?php

declare(strict_types=1);

namespace App\Subscriber\Event\Order;

use App\Event\Domain\Order\OrderDeliveredEvent;
use App\Event\Domain\Order\OrderReturnWindowExpiredEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class OrderShipmentSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger)
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
