<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Order as RootOrder;

final class OrderDispute
{
    private string $status = 'open';
    public function __construct(private RootOrder|string $order, private string $type, private ?string $reason = null, private ?string $externalId = null) {}
    public function resolve(): void { $this->status = 'resolved'; }
    public function status(): string { return $this->status; }
}
