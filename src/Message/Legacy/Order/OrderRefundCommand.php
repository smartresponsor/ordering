<?php

declare(strict_types=1);

namespace App\Ordering\Message\Legacy\Order;

final readonly class OrderRefundCommand
{
    public function __construct(public string $orderId, public string $amount, public ?string $reason = null)
    {
    }
}
