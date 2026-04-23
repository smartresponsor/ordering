<?php

declare(strict_types=1);

namespace App\Entity\Order;

final readonly class OrderPriceAudit
{
    public function __construct(private string $orderId, private string $operation, private array $payload = [])
    {
    }
}
