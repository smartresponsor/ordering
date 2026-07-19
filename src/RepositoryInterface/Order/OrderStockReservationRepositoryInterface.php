<?php

declare(strict_types=1);

namespace App\Ordering\RepositoryInterface\Order;

use App\Ordering\Entity\Order\OrderStockReservationEntity;

interface OrderStockReservationRepositoryInterface
{
    public function save(OrderStockReservationEntity $reservation): void;

    public function add(OrderStockReservationEntity $reservation): void;

    public function findOne(string $orderId, string $sku): ?OrderStockReservationEntity;

    /** @return list<OrderStockReservationEntity> */
    public function findActiveForSku(string $sku): array;
}
