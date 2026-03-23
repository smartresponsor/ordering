<?php

declare(strict_types=1);

namespace App\Service\Order;

final readonly class OrderCreateCommand
{
    public function __construct(
        public string $currency,
        public string $grandTotal,
    ) {
    }
}
