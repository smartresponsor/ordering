<?php

declare(strict_types=1);

namespace App\Ordering\Message\Command\Order;

final readonly class RecalculateOrderPricingCommand
{
    public function __construct(public string $orderId)
    {
    }
}
