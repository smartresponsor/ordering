<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

use App\Entity\Order\OrderEntity;

final readonly class OrderRefundedEvent
{
    public function __construct(public OrderEntity $order, public string $amount)
    {
    }
}
