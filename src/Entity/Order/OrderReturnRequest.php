<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class OrderReturnRequest
{
    private string $status = 'draft';

    public function __construct(
        private string $id,
        private string $orderId,
        private int $amountMinor,
        private string $currency,
        private ?string $reason = null,
    ) {}

    public function id(): string { return $this->id; }
    public function approve(): void { $this->status = 'approved'; }
    public function markRefunded(): void { $this->status = 'refunded'; }
}
