<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use App\Message\Command\OrderPayCommand;
use App\Service\Outbox\OutboxPublisher;
use Doctrine\ORM\EntityManagerInterface;

interface OrderPayHandlerInterface
{
    public function __construct(EntityManagerInterface $em, OutboxPublisher $outbox);

    public function __invoke(OrderPayCommand $cmd): void;
}
