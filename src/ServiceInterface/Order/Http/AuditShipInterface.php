<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order\Http;

interface AuditShipInterface
{
    public function ship(string $path): void;
}
