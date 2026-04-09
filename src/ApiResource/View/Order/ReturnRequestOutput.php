<?php

declare(strict_types=1);

namespace App\ApiResource\View\Order;

final readonly class ReturnRequestOutput
{
    public function __construct(
        public readonly string $returnRequestId,
        public readonly string $status,
    ) {
    }
}
