<?php

declare(strict_types=1);

namespace App\Service\Order;

final class StartOrderSagaCommand
{
    public function __construct(public readonly int $orderId)
    {
    }
}
