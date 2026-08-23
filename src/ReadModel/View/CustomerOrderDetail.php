<?php

declare(strict_types=1);

namespace App\Ordering\ReadModel\View;

final readonly class CustomerOrderDetail
{
    /** @param list<CustomerOrderItemSummary> $items */
    public function __construct(
        public string $reference,
        public string $number,
        public string $status,
        public string $currency,
        public string $grandTotal,
        public string $createdAt,
        public string $updatedAt,
        public array $items,
    ) {
    }
}
