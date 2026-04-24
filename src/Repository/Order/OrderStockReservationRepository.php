<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderStockReservation;
use App\RepositoryInterface\Order\OrderStockReservationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderStockReservationRepository implements OrderStockReservationRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(OrderStockReservation $reservation): void
    {
        $this->add($reservation);
    }

    public function add(OrderStockReservation $reservation): void
    {
        $this->em->persist($reservation);
    }

    public function findOne(string $orderId, string $sku): ?OrderStockReservation
    {
        $reservation = $this->em->getRepository(OrderStockReservation::class)->findOneBy(['orderId' => $orderId, 'sku' => $sku]);

        return $reservation instanceof OrderStockReservation ? $reservation : null;
    }

    public function findActiveForSku(string $sku): array
    {
        $reservations = $this->em->getRepository(OrderStockReservation::class)->findBy(['sku' => $sku, 'status' => OrderStockReservation::STATUS_RESERVED]);

        return array_values(array_filter($reservations, static fn (mixed $reservation): bool => $reservation instanceof OrderStockReservation));
    }
}
