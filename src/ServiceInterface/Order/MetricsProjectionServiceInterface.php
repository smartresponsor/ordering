<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order\Order;
use Doctrine\ORM\EntityManagerInterface;

interface MetricsProjectionServiceInterface
{
    public function __construct(EntityManagerInterface $em);

    public function projectOrderPlaced(Order $order, string $amount, string $vendorId, \DateTimeImmutable $at): void;

    public function projectRefund(string $amount, \DateTimeImmutable $at): void;
}
