<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderStockReservationEntity;
use App\RepositoryInterface\Order\OrderStockReservationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderStockReservationRepository implements OrderStockReservationRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(OrderStockReservationEntity $reservation): void
    {
        $this->add($reservation);
    }

    public function add(OrderStockReservationEntity $reservation): void
    {
        $this->em->persist($reservation);
    }

    public function findOne(string $orderId, string $sku): ?OrderStockReservationEntity
    {
        $reservation = $this->em->getRepository(OrderStockReservationEntity::class)->findOneBy(['orderId' => $orderId, 'sku' => $sku]);

        return $reservation instanceof OrderStockReservationEntity ? $reservation : null;
    }

    public function findActiveForSku(string $sku): array
    {
        $reservations = $this->em->getRepository(OrderStockReservationEntity::class)->findBy(['sku' => $sku, 'status' => OrderStockReservationEntity::STATUS_RESERVED]);

        return array_values(array_filter($reservations, static fn (mixed $reservation): bool => $reservation instanceof OrderStockReservationEntity));
    }
}
