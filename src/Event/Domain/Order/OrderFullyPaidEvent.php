<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

use App\Ordering\Entity\Order\OrderEntity;

final readonly class OrderFullyPaidEvent
{
    public function __construct(public OrderEntity $order)
    {
    }
}
