<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

final readonly class ContinueOrderSagaCommand
{
    public function __construct(public int $orderId, public string $step)
    {
    }
}
