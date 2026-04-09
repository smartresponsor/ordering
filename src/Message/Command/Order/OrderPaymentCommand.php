<?php

declare(strict_types=1);

namespace App\Message\Command\Order;

final readonly class OrderPaymentCommand
{
    public function __construct(public readonly string $orderId, public readonly string $amount) {
    }
}
