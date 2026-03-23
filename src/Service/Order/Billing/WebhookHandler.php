<?php

declare(strict_types=1);

namespace App\Service\Order\Billing;

use App\Service\Order\WebhookHandler as BaseWebhookHandler;
use Symfony\Component\HttpFoundation\Request;

final readonly class WebhookHandler
{
    public function __construct(private BaseWebhookHandler $handler)
    {
    }

    public function handlePayment(Request $request): array
    {
        return $this->handler->handlePayment($request);
    }

    public function handleRefund(Request $request): array
    {
        return $this->handler->handleRefund($request);
    }
}
