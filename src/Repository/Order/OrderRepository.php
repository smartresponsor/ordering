<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Repository\Order;

use App\Entity\Order\Order;
use App\RepositoryInterface\Order\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function findById(string $id): ?Order
    {
        return $this->em->getRepository(Order::class)->find($id);
    }

    public function save(Order $order): void
    {
        $this->em->persist($order);
        // flush вызывается снаружи (в сервисе статусов) для транзакционной целостности
    }
}
