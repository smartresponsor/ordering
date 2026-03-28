<?php

declare(strict_types=1);

namespace App\ServiceInterface\Payment;

use App\Entity\Order;

interface PaymentGatewayInterface
{
    public function charge(Order $order, int $amount): string;
}
