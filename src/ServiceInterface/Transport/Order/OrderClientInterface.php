<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Transport\Order;

use App\Ordering\Entity\Order\OrderEntity;

interface OrderClientInterface
{
    public function createOrder(int $totalAmount, string $currency, string $customerId): OrderEntity;

    public function getOrder(string $orderId): OrderEntity;

    public function transition(string $orderId, string $action, ?string $idempotencyKey = null): void;
}
