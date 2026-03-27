<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order\Http;

interface AuditRotateInterface
{
    public function rotate(): void;
}
