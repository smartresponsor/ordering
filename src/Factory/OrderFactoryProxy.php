<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Order;

final readonly class OrderFactoryProxy
{
    public function __construct(private Order $order)
    {
    }

    public function object(): Order
    {
        return $this->order;
    }
}
