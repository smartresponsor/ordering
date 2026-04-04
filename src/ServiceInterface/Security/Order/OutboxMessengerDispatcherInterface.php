<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

interface OutboxMessengerDispatcherInterface
{
    public function __construct(EntityManagerInterface $em, MessageBusInterface $bus);

    public function dispatchPending(int $limit = 100): int;
}
