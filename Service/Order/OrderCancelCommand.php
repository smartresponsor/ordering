<?php

declare(strict_types=1);

namespace App\Service\Order;

final class OrderCancelCommand
{
    public function __construct(public string $orderId)
    {
    }
}
