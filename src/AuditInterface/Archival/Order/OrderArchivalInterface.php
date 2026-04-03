<?php

declare(strict_types=1);

namespace App\AuditInterface\Archival\Order;

interface OrderArchivalInterface
{
    public function archive(string $aggregateId): bool;
}
