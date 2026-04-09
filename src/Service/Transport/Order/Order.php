<?php

declare(strict_types=1);

namespace App\Service\Transport\Order;

final readonly class Order
{
    public function __construct(
        public readonly string $id,
        public readonly string $status,
        public readonly int $totalAmount,
        public readonly string $currency,
        public readonly ?string $customerId = null,
    ) {
    }

    public static function fromArray(array $a): self
    {
        return new self($a['id'], $a['status'], $a['totalAmount'], $a['currency'], $a['customerId'] ?? null);
    }
}
