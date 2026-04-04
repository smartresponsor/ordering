<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

final readonly class OrderCreateCommand
{
    public function __construct(
        public string $currency,
        public string $grandTotal,
    ) {
    }
}
