<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order\Order;
use App\Entity\Order\OrderDispute;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

interface DisputeServiceInterface
{
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $events,
    );

    public function openDispute(Order $order, string $type, ?string $reason = null, ?string $externalId = null): OrderDispute;

    public function resolveDispute(OrderDispute $dispute): void;

    public function issueChargeback(OrderDispute $dispute): void;
}
