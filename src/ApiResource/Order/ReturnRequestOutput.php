<?php

declare(strict_types=1);

namespace App\ApiResource\Order;

final readonly class ReturnRequestOutput
{
    public function __construct(
        public string $returnRequestId,
        public string $status,
    ) {
    }
}
