<?php

declare(strict_types=1);

namespace App\Message\Handler\Order;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderPartialPaymentHandler
{
    public function __invoke(object $message): void
    {
    }
}
