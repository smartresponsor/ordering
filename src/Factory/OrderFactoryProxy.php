<?php

declare(strict_types=1);

namespace App\Factory;

use App\Ordering\Entity\Order\OrderEntity;

final readonly class OrderFactoryProxy
{
    public function __construct(private OrderEntity $order)
    {
    }

    public function object(): OrderEntity
    {
        return $this->order;
    }
}
