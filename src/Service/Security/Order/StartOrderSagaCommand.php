<?php

declare(strict_types=1);

namespace App\Ordering\Service\Security\Order;

final readonly class StartOrderSagaCommand
{
    public function __construct(public int $orderId)
    {
    }
}
