<?php

declare(strict_types=1);

namespace App\RepositoryInterface\Order;

use App\Entity\Order\OrderStockReservation;

interface OrderStockReservationRepositoryInterface
{
    public function save(OrderStockReservation $reservation): void;
    /** @return list<OrderStockReservation> */
    public function findActiveForSku(string $sku): array;
}
