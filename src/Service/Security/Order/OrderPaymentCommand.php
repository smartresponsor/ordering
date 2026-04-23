<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

final readonly class OrderPaymentCommand
{
    public function __construct(public string $orderId, public string $amount)
    {
    }
}
