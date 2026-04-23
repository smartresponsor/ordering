<?php

declare(strict_types=1);

namespace App\Message\Command;

final readonly class StartOrderSagaCommand
{
    public function __construct(public string $orderId, public array $context = [])
    {
    }
}
