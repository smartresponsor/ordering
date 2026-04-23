<?php

declare(strict_types=1);

namespace App\Controller\Webhook;

use App\Service\Billing\Order\WebhookHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final readonly class PaymentWebhookController
{
    public function __construct(private WebhookHandler $handler)
    {
    }

    /**
     * @throws \Exception
     */
    #[Route(path: '/api/webhooks/payment', name: 'order_payment_webhook', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->handler->handlePayment($request);

        return new JsonResponse($data, 200);
    }

    #[Route(path: '/api/webhooks/refund', name: 'order_refund_webhook', methods: ['POST'])]
    public function refund(Request $request): JsonResponse
    {
        $data = $this->handler->handleRefund($request);

        return new JsonResponse($data, 200);
    }
}
