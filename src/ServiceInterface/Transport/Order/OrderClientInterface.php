<?php

declare(strict_types=1);

namespace App\ServiceInterface\Transport\Order;

use App\Service\Transport\Order\Order;

interface OrderClientInterface
{
    public function createOrder(int $totalAmount, string $currency, string $customerId): Order;

    public function getOrder(string $orderId): Order;

    public function transition(string $orderId, string $action, ?string $idempotencyKey = null): void;
}
