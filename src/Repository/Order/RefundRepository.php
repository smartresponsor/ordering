<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Repository\Order;

use App\Entity\Order\Refund;
use App\RepositoryInterface\Order\RefundRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class RefundRepository implements RefundRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function add(Refund $r): void
    {
        $this->em->persist($r);
    }
}
