<?php

declare(strict_types=1);

namespace App\Ordering\Webhook;

use App\Ordering\ServiceInterface\Webhook\Order\WebhookDispatcherInterface;

final readonly class NoopWebhookDispatcher implements WebhookDispatcherInterface
{
    public function dispatch(object $event): void
    {
    }
}
