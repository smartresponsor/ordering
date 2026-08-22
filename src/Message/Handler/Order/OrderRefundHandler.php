<?php

declare(strict_types=1);

namespace App\Ordering\Message\Handler\Order;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderRefundHandler
{
    public function __invoke(object $message): void
    {
    }
}
