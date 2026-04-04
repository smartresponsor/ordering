<?php

declare(strict_types=1);

namespace App\ServiceInterface\Http\Order;

interface AuditShipInterface
{
    /**
     * @return array{uploaded:int, keys:list<string>, etag?:string}
     */
    public function ship(
        string $date,
        ?string $bucket = null,
        ?string $prefix = null,
        ?string $sse = null,
        ?string $kmsKey = null,
    ): array;
}
