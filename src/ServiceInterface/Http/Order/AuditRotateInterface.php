<?php

declare(strict_types=1);

namespace App\ServiceInterface\Http\Order;

interface AuditRotateInterface
{
    /**
     * @return array{rotated:int, manifest:string, checksum:string}
     */
    public function rotate(int $olderThanDays = 0, bool $gzip = true): array;
}
