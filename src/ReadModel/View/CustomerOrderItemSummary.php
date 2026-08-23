<?php

declare(strict_types=1);

namespace App\Ordering\ReadModel\View;

final readonly class CustomerOrderItemSummary
{
    public function __construct(
        public string $reference,
        public int $quantity,
        public string $currency,
        public string $unitPrice,
    ) {
    }
}
