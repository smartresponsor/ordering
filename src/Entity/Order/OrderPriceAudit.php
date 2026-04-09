<?php

declare(strict_types=1);

namespace App\Entity\Order;

final readonly class OrderPriceAudit
{
    public function __construct(private readonly string $orderId, private readonly string $operation, private readonly array $payload = []) {}
}
