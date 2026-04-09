<?php

declare(strict_types=1);

namespace App\Message\Command;

final readonly class StartOrderSagaCommand
{
    public function __construct(public readonly string $orderId, public readonly array $context = []) {
    }
}
