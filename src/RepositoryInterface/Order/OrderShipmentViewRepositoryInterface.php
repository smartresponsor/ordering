<?php

declare(strict_types=1);

namespace App\Ordering\RepositoryInterface\Order;

use App\Entity\OrderShipmentView;

interface OrderShipmentViewRepositoryInterface
{
    public function find(string $orderId): ?OrderShipmentView;

    public function save(OrderShipmentView $view): void;
}
