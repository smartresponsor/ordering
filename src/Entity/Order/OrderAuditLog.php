<?php

declare(strict_types=1);

namespace App\Entity\Order;

final readonly class OrderAuditLog
{
    public function __construct(
        private readonly string $id,
        private readonly string $orderId,
        private readonly string $action,
        private readonly ?string $payload = null,
    ) {
    }

    public function orderId(): string
    {
        return $this->orderId;
    }
}
