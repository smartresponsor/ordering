<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Message\Order\OrderRefundCommand;
use App\Service\Order\WebhookIdempotencyService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RefundWebhookController
{
    public function __construct(
        private WebhookIdempotencyService $idem,
        private MessageBusInterface $bus,
    ) {
    }

    #[Route('/webhooks/refund', name: 'order_refund_webhook', methods: ['POST'])]
    public function __invoke(Request $req): JsonResponse
    {
        $idemKey = $req->headers->get('Idempotency-Key') ?? $req->get('event_id', '');
        if (!$idemKey) {
            return new JsonResponse(['error' => 'missing idempotency key'], 400);
        }
        $rawPayload = $req->getContent() ?: '{}';
        $payload = json_decode($rawPayload, true);
        if (!is_array($payload)) {
            $payload = [];
        }
        $orderId = (string) ($payload['orderId'] ?? '');
        $amount = (string) ($payload['amount'] ?? '0.00');
        $reason = (string) ($payload['reason'] ?? '');

        $accepted = $this->idem->handleOnce(
            $idemKey,
            'refund',
            $rawPayload,
            function () use ($orderId, $amount, $reason): void {
                if ('' !== $orderId && '' !== $amount) {
                    $this->bus->dispatch(new OrderRefundCommand($orderId, $amount, '' !== $reason ? $reason : null));
                }
            }
        );

        return new JsonResponse(['status' => $accepted ? 'ok' : 'duplicate'], $accepted ? 202 : 200);
    }
}
