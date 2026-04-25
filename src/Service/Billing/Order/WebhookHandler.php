<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Billing\Order;

use App\ServiceInterface\Security\Order\OrderIdempotencyGuardInterface;
use App\ServiceInterface\Webhook\Order\OrderWebhookHandlerInterface;
use App\ServiceInterface\Webhook\Order\WebhookHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

final readonly class WebhookHandler implements WebhookHandlerInterface, OrderWebhookHandlerInterface
{
    public function __construct(
        private ?OrderIdempotencyGuardInterface $guard = null,
    ) {
    }

    public function handlePayment(Request $request): array
    {
        $provider = (string) $request->headers->get('X-Provider', 'mock');
        $eventId = (string) $request->headers->get('X-Event-Id', bin2hex(random_bytes(6)));
        $payload = $request->getContent() ?: '{}';

        if (null !== $this->guard && !$this->guard->checkAndPersist($provider, $eventId, $payload)) {
            return ['status' => 'ignored', 'reason' => 'duplicate'];
        }

        return ['status' => 'ok', 'provider' => $provider, 'eventId' => $eventId];
    }

    public function handleRefund(Request $request): array
    {
        // For brevity: re-use handlePayment shape
        return $this->handlePayment($request);
    }
}
