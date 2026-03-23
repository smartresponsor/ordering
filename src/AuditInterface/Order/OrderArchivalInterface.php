<?php

declare(strict_types=1);

namespace App\AuditInterface\Order;

interface OrderArchivalInterface
{
    public function archive(string $aggregateId): bool;
}
