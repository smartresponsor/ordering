<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class OrderReturnRequest
{
    private string $status = 'draft';

    public function __construct(
        private readonly string $id,
        private readonly string $orderId,
        private readonly int $amountMinor,
        private readonly string $currency,
        private readonly ?string $reason = null,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function approve(): void
    {
        $this->status = 'approved';
    }

    public function markRefunded(): void
    {
        $this->status = 'refunded';
    }
}
