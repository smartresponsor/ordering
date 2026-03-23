<?php

declare(strict_types=1);

namespace App\Subscriber\Order;

use App\Event\Order\OrderChargebackIssuedEvent;
use App\Event\Order\OrderDisputeOpenedEvent;
use App\Event\Order\OrderDisputeResolvedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OrderDisputeSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderDisputeOpenedEvent::class => 'onOpened',
            OrderDisputeResolvedEvent::class => 'onResolved',
            OrderChargebackIssuedEvent::class => 'onChargeback',
        ];
    }

    public function onOpened(OrderDisputeOpenedEvent $event): void
    {
        $this->logger->warning('Dispute opened', [
            'disputeId' => $event->dispute->getId(),
            'status' => $event->dispute->getStatus(),
        ]);
    }

    public function onResolved(OrderDisputeResolvedEvent $event): void
    {
        $this->logger->info('Dispute resolved', [
            'disputeId' => $event->dispute->getId(),
            'status' => $event->dispute->getStatus(),
        ]);
    }

    public function onChargeback(OrderChargebackIssuedEvent $event): void
    {
        $this->logger->error('Chargeback issued', [
            'disputeId' => $event->dispute->getId(),
            'status' => $event->dispute->getStatus(),
        ]);
    }
}
