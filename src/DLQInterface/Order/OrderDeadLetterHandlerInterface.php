<?php

declare(strict_types=1);

namespace App\DLQInterface\Order;

interface OrderDeadLetterHandlerInterface
{
    public function handle(array $message): void;
}
