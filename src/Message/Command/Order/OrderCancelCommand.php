<?php

declare(strict_types=1);

namespace App\Ordering\Message\Command\Order;

final readonly class OrderCancelCommand
{
    public function __construct(public string $orderId)
    {
    }
}
