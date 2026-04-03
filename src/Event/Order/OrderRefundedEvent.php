<?php

declare(strict_types=1);

namespace App\Event\Order;

use App\Entity\Order;

final readonly class OrderRefundedEvent
{
    public function __construct(public Order $order, public string $amount)
    {
    }
}
