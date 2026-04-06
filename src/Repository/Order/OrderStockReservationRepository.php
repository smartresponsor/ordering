<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderStockReservation;
use App\RepositoryInterface\Order\OrderStockReservationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderStockReservationRepository implements OrderStockReservationRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}
    public function save(OrderStockReservation $reservation): void { $this->em->persist($reservation); }
    public function findActiveForSku(string $sku): array { return []; }
}
