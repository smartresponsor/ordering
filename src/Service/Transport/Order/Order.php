<?php

declare(strict_types=1);

namespace App\Ordering\Service\Transport\Order;

final readonly class Order
{
    public function __construct(
        public string $id,
        public string $status,
        public int $totalAmount,
        public string $currency,
        public ?string $customerId = null,
    ) {
    }

    public static function fromArray(array $a): self
    {
        return new self($a['id'], $a['status'], $a['totalAmount'], $a['currency'], $a['customerId'] ?? null);
    }
}
