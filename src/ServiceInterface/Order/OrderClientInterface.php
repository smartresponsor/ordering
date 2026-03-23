<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use App\Service\Order\Order;

interface OrderClientInterface
{
    public function createOrder(int $totalAmount, string $currency, string $customerId): Order;

    public function getOrder(string $orderId): Order;

    public function transition(string $orderId, string $action, ?string $idempotencyKey = null): void;
}
