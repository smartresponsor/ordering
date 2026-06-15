<?php

declare(strict_types=1);

namespace App\ServiceInterface\Payment;

use App\Entity\Order\OrderEntity;

interface PaymentGatewayInterface
{
    public function charge(OrderEntity $OrderEntity, int $amount): string;
}
