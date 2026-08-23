<?php

declare(strict_types=1);

namespace App\Ordering\MessageHandler;

use App\Messenger\Message\OutboxDispatchedMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
final readonly class OutboxDispatchedMessageHandler
{
    public function __invoke(OutboxDispatchedMessage $message): void
    {
        // Canonical no-op dispatch sink. Real external delivery adapters should decorate this handler.
        unset($message);
    }
}
