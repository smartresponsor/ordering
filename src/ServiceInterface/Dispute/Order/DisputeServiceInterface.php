<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Dispute\Order;

use App\Ordering\Entity\Order\OrderDisputeEntity;
use App\Ordering\Entity\Order\OrderEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

interface DisputeServiceInterface
{
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $events,
    );

    public function openDispute(OrderEntity $order, string $type, ?string $reason = null, ?string $externalId = null): OrderDisputeEntity;

    public function resolveDispute(OrderDisputeEntity $dispute): void;

    public function issueChargeback(OrderDisputeEntity $dispute): void;
}
