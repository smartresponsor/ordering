<?php

declare(strict_types=1);

namespace App\Webhook;

use App\ServiceInterface\Webhook\Order\WebhookDispatcherInterface;

final class NoopWebhookDispatcher implements WebhookDispatcherInterface
{
    public function dispatch(object $event): void
    {
    }
}
