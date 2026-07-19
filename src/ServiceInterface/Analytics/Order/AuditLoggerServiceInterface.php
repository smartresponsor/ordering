<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Analytics\Order;

use App\Ordering\Entity\Order\OrderEntity;
use Doctrine\ORM\EntityManagerInterface;

interface AuditLoggerServiceInterface
{
    public function __construct(EntityManagerInterface $em);

    public function logEvent(OrderEntity $OrderEntity, string $event, array $context = [], ?string $actor = null, ?string $ip = null): void;
}
