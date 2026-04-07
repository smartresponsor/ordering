<?php

declare(strict_types=1);

namespace App\RepositoryInterface\Order;

use App\Entity\Order\OrderStockReservation;

interface OrderStockReservationRepositoryInterface
{
    public function save(OrderStockReservation $reservation): void;

    public function add(OrderStockReservation $reservation): void;

    public function findOne(string $orderId, string $sku): ?OrderStockReservation;

    /** @return list<OrderStockReservation> */
    public function findActiveForSku(string $sku): array;
}
