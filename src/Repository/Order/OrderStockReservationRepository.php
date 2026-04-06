<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderStockReservation;
use App\RepositoryInterface\Order\OrderStockReservationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderStockReservationRepository implements OrderStockReservationRepositoryInterface
{
    /** @var list<OrderStockReservation> */
    private static array $reservations = [];

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(OrderStockReservation $reservation): void
    {
        self::$reservations[] = $reservation;
        $this->em->persist($reservation);
    }

    public function findActiveForSku(string $sku): array
    {
        return array_values(array_filter(
            self::$reservations,
            static fn (OrderStockReservation $reservation): bool => $reservation->sku() === $sku && !$reservation->isReleased(),
        ));
    }
}
