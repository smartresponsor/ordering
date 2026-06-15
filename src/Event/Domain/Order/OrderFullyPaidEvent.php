<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

use App\Entity\Order\OrderEntity;

final readonly class OrderFullyPaidEvent
{
    public function __construct(public OrderEntity $order)
    {
    }
}
