<?php

declare(strict_types=1);

namespace App\Service\Order\Http;

use App\ServiceInterface\Order\Http\AuditSinkInterface;

final class MonologAuditSink implements AuditSinkInterface
{
    public function write(array $payload): void
    {
    }
}
