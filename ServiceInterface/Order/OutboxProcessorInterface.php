<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use App\Repository\Outbox\OutboxMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

interface OutboxProcessorInterface
{
    public function __construct(
        OutboxMessageRepository $repo,
        EntityManagerInterface $em,
        MessageBusInterface $bus,
        LoggerInterface $logger,
    );

    public function dispatchBatch(int $limit = 50): int;
}
