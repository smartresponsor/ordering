<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order\Http;

interface AuditSinkInterface
{
    public function write(array $payload): void;
}
