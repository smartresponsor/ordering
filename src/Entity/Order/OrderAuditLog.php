<?php

declare(strict_types=1);

namespace App\Entity\Order;

final readonly class OrderAuditLog
{
    public function __construct(
        private string $id,
        private string $orderId,
        private string $action,
        private ?string $payload = null,
    ) {
    }

    public function orderId(): string
    {
        return $this->orderId;
    }
}
