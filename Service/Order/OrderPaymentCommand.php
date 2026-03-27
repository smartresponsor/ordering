<?php

declare(strict_types=1);

namespace App\Service\Order;

final class OrderPaymentCommand
{
    public function __construct(public string $orderId, public string $amount)
    {
    }
}
