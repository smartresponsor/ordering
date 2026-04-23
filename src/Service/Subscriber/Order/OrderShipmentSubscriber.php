<?php

declare(strict_types=1);

namespace App\Service\Subscriber\Order;

use App\Event\Domain\Order\OrderDeliveredEvent;
use App\Event\Domain\Order\OrderReturnWindowExpiredEvent;
use App\ServiceInterface\Subscriber\Order\OrderShipmentSubscriberInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final readonly class OrderShipmentSubscriber implements OrderShipmentSubscriberInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[AsEventListener(event: OrderDeliveredEvent::class)]
    public function onDelivered(OrderDeliveredEvent $e): void
    {
        $this->logger->info('Order delivered', ['shipmentId' => $e->shipment->getId()]);
    }

    #[AsEventListener(event: OrderReturnWindowExpiredEvent::class)]
    public function onReturnExpired(OrderReturnWindowExpiredEvent $e): void
    {
        $this->logger->info('Return window expired', ['shipmentId' => $e->policy->getAutoExpireDate()?->format(\DateTimeInterface::ATOM)]);
    }
}
