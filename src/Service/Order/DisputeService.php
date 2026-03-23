<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\Order;
use App\Entity\Order\OrderDispute;
use App\Event\Order\OrderChargebackIssuedEvent;
use App\Event\Order\OrderDisputeOpenedEvent;
use App\Event\Order\OrderDisputeResolvedEvent;
use App\ServiceInterface\Order\DisputeServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class DisputeService implements DisputeServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $events,
    ) {
    }

    public function openDispute(Order $order, string $type, ?string $reason = null, ?string $externalId = null): OrderDispute
    {
        $dispute = new OrderDispute($order, $type, $reason, $externalId);
        $this->em->persist($dispute);
        $this->em->flush();
        $this->events->dispatch(new OrderDisputeOpenedEvent($dispute));

        return $dispute;
    }

    public function resolveDispute(OrderDispute $dispute): void
    {
        $dispute->markResolved();
        $this->em->flush();
        $this->events->dispatch(new OrderDisputeResolvedEvent($dispute));
    }

    public function issueChargeback(OrderDispute $dispute): void
    {
        $dispute->setStatus(OrderDispute::STATUS_CHARGEBACK_ISSUED);
        $this->em->flush();
        $this->events->dispatch(new OrderChargebackIssuedEvent($dispute));
    }
}
