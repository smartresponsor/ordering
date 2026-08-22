<?php

declare(strict_types=1);

namespace App\Ordering\MessageHandler\Outbox;

use App\Ordering\Message\Outbox\OrderOutboxDispatchedMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
final readonly class OrderOutboxDispatchedMessageHandler
{
    public function __invoke(OrderOutboxDispatchedMessage $message): void
    {
        throw new \RuntimeException(sprintf('Outbox dispatch failed for topic "%s".', $message->topic));
    }
}
