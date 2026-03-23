<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Repository\Order;

use App\Entity\Order\OrderStockReservation;
use App\RepositoryInterface\Order\OrderStockReservationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderStockReservationRepository implements OrderStockReservationRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function add(OrderStockReservation $res): void
    {
        $this->em->persist($res);
    }

    public function findByOrder(string $orderId): iterable
    {
        return $this->em->getRepository(OrderStockReservation::class)->findBy(['orderId' => $orderId]);
    }

    public function findOne(string $orderId, string $sku): ?OrderStockReservation
    {
        return $this->em->getRepository(OrderStockReservation::class)->findOneBy(['orderId' => $orderId, 'sku' => $sku]);
    }
}
