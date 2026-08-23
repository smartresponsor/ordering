<?php

declare(strict_types=1);

namespace App\Ordering\Service\Security\Order;

final readonly class RetryOrderSagaCommand
{
    public function __construct(public int $orderId)
    {
    }
}
