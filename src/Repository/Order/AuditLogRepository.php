<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Repository\Order;

use App\Entity\Order\AuditLog;
use App\RepositoryInterface\Order\AuditLogRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class AuditLogRepository implements AuditLogRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function add(AuditLog $log): void
    {
        $this->em->persist($log);
        $this->em->flush();
    }
}
