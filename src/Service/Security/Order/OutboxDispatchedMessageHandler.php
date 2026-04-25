<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\Messenger\Message\OutboxDispatchedMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
final readonly class OutboxDispatchedMessageHandler
{
    public function __invoke(OutboxDispatchedMessage $message): void
    {
        throw new \RuntimeException(sprintf('Outbox dispatch failed for topic "%s".', $message->topic));
    }
}
