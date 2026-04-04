<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use App\RepositoryInterface\Order\OutboxRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

interface OutboxRelayInterface
{
    public function __construct(
        EntityManagerInterface $em,
        OutboxRepositoryInterface $repo,
        TransactionalEventPublisherInterface $publisher,
        LoggerInterface $logger,
    );

    public function runOnce(int $batchSize = 50): int;
}
