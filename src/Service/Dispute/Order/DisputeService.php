<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Dispute\Order;

use App\Ordering\Entity\Order\OrderDisputeEntity;
use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Event\Domain\Order\OrderChargebackIssuedEvent;
use App\Ordering\Event\Domain\Order\OrderDisputeOpenedEvent;
use App\Ordering\Event\Domain\Order\OrderDisputeResolvedEvent;
use App\Ordering\ServiceInterface\Dispute\Order\DisputeServiceInterface;
use App\Ordering\ServiceInterface\Dispute\Order\OrderDisputeServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class DisputeService implements DisputeServiceInterface, OrderDisputeServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private EventDispatcherInterface $events,
    ) {
    }

    public function openDispute(OrderEntity $order, string $type, ?string $reason = null, ?string $externalId = null): OrderDisputeEntity
    {
        $dispute = new OrderDisputeEntity($order, $type, $reason, $externalId);
        $this->em->persist($dispute);
        $this->em->flush();
        $this->events->dispatch(new OrderDisputeOpenedEvent($dispute));

        return $dispute;
    }

    public function resolveDispute(OrderDisputeEntity $dispute): void
    {
        $dispute->markResolved();
        $this->em->flush();
        $this->events->dispatch(new OrderDisputeResolvedEvent($dispute));
    }

    public function issueChargeback(OrderDisputeEntity $dispute): void
    {
        $dispute->setStatus(OrderDisputeEntity::STATUS_CHARGEBACK_ISSUED);
        $this->em->flush();
        $this->events->dispatch(new OrderChargebackIssuedEvent($dispute));
    }
}
