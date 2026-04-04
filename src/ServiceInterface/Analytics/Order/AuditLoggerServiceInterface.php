<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Analytics\Order;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

interface AuditLoggerServiceInterface
{
    public function __construct(EntityManagerInterface $em);

    public function logEvent(Order $order, string $event, array $context = [], ?string $actor = null, ?string $ip = null): void;
}
