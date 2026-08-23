<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Payment;

use App\Ordering\Entity\Order\OrderEntity;

interface PaymentGatewayInterface
{
    public function charge(OrderEntity $OrderEntity, int $amount): string;
}
