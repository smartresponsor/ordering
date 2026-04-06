<?php

declare(strict_types=1);

namespace App\RepositoryInterface\Order;

use App\Entity\Order\OrderShipmentView;

interface OrderShipmentViewRepositoryInterface
{
    public function find(string $orderId): ?OrderShipmentView;

    public function save(OrderShipmentView $view): void;
}
