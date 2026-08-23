<?php

declare(strict_types=1);

namespace App\Ordering\Message\Command;

final readonly class RetryOrderSagaCommand
{
    public function __construct(public string $orderId, public int $attempt = 1, public array $context = [])
    {
    }
}
