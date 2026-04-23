<?php

declare(strict_types=1);

namespace App\Subscriber\Event\Order;

use App\Event\Domain\Order\OrderChargebackIssuedEvent;
use App\Event\Domain\Order\OrderDisputeOpenedEvent;
use App\Event\Domain\Order\OrderDisputeResolvedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class OrderDisputeSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger)
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
