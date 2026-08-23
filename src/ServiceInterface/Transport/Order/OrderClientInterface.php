<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Transport\Order;

use App\Ordering\Service\Transport\Order\Order;

interface OrderClientInterface
{
    public function createOrder(int $totalAmount, string $currency, string $customerId): Order;

    public function getOrder(string $orderId): Order;

    public function transition(string $orderId, string $action, ?string $idempotencyKey = null): void;
}
