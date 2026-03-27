<?php

declare(strict_types=1);

namespace App\Service\Order\Outbox;

final class ExponentialBackoffStrategy
{
    public function nextDelaySeconds(int $attempt): int
    {
        $attempt = max(1, $attempt);

        return min(3600, 2 ** ($attempt - 1));
    }
}
