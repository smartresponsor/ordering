<?php

declare(strict_types=1);

namespace App\Message\Command\Order;

final class OrderCancelCommand
{
    public function __construct(public string $orderId)
    {
    }
}
