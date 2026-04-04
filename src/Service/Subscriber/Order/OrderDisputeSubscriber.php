<?php

declare(strict_types=1);

namespace App\Service\Subscriber\Order;

use App\Event\Domain\Order\OrderChargebackIssuedEvent;
use App\Event\Domain\Order\OrderDisputeOpenedEvent;
use App\Event\Domain\Order\OrderDisputeResolvedEvent;
use App\ServiceInterface\Subscriber\Order\OrderDisputeSubscriberInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class OrderDisputeSubscriber implements OrderDisputeSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    #[AsEventListener(event: OrderDisputeOpenedEvent::class)]
    public function onOpened(OrderDisputeOpenedEvent $e): void
    {
        $this->logger->warning('Dispute opened', ['disputeId' => $e->dispute->getId(), 'status' => $e->dispute->getStatus()]);
    }

    #[AsEventListener(event: OrderDisputeResolvedEvent::class)]
    public function onResolved(OrderDisputeResolvedEvent $e): void
    {
        $this->logger->info('Dispute resolved', ['disputeId' => $e->dispute->getId(), 'status' => $e->dispute->getStatus()]);
    }

    #[AsEventListener(event: OrderChargebackIssuedEvent::class)]
    public function onChargeback(OrderChargebackIssuedEvent $e): void
    {
        $this->logger->error('Chargeback issued', ['disputeId' => $e->dispute->getId(), 'status' => $e->dispute->getStatus()]);
    }
}
