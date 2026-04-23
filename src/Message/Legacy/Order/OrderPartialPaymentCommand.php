<?php

declare(strict_types=1);

namespace App\Message\Legacy\Order;

final readonly class OrderPartialPaymentCommand
{
    public function __construct(public string $orderId, public string $amount, public string $method = 'card')
    {
    }
}
