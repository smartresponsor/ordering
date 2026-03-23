<?php

declare(strict_types=1);

namespace App\Service\Order;

final class ContinueOrderSagaCommand
{
    public function __construct(public readonly int $orderId, public readonly string $step)
    {
    }
}
