<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Order;

use App\Ordering\Entity\Order\OrderEntity;

interface OrderCreationServiceInterface
{
    public function create(string $customerId, string $vendorId, string $currency, string $grandTotal): OrderEntity;
}
