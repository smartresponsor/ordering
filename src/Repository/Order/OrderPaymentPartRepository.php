<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Repository\Order;

use App\Entity\Order\OrderPaymentPart;
use App\RepositoryInterface\Order\OrderPaymentPartRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderPaymentPartRepository implements OrderPaymentPartRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function add(OrderPaymentPart $p): void
    {
        $this->em->persist($p);
    }

    /** @return OrderPaymentPart[] */
    public function listByOrder(string $orderId): array
    {
        return $this->em->getRepository(OrderPaymentPart::class)->findBy(['orderId' => $orderId], ['id' => 'ASC']);
    }

    public function sumByOrder(string $orderId): string
    {
        $conn = $this->em->getConnection();
        $stmt = $conn->prepare('SELECT COALESCE(SUM(amount),0) AS total FROM order_payment_part WHERE order_id = :id');
        $res = $stmt->executeQuery(['id' => $orderId])->fetchAssociative();

        return $res['total'] ?? '0';
    }
}
