<?php

declare(strict_types=1);

namespace App\Support\Security\Order;

final class Idempotency
{
    public static function key(): string
    {
        return 'key-'.time().'-'.bin2hex(random_bytes(4));
    }
}
