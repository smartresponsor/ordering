<?php

declare(strict_types=1);

namespace App\Ordering\Message\Command;

final readonly class OrderCreateCommand
{
    public function __construct(
        public string $currency,
        public string $grandTotal,
    ) {
    }
}
