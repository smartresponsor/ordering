<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Refund\Order;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

interface RefundEligibilityServiceInterface
{
    public function __construct(EntityManagerInterface $em);

    public function canRefund(Order $order): bool;
}
