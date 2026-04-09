<?php

declare(strict_types=1);

namespace App\Service\Http\Order;

use App\ServiceInterface\Http\Order\AuditSinkInterface;

final readonly class MonologAuditSink implements AuditSinkInterface
{
    public function write(array $payload): void
    {
    }
}
