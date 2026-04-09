<?php

declare(strict_types=1);

namespace App\Message\Legacy\Order;

final readonly class OrderPartialPaymentCommand
{
    public function __construct(public readonly string $orderId, public readonly string $amount, public readonly string $method = 'card') {
    }
}
