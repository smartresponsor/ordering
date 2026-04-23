<?php

declare(strict_types=1);

namespace App\Http\Subscriber;

use App\Security\WebhookSignatureVerifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final readonly class WebhookHmacSubscriber
{
    public function __construct(
        private WebhookSignatureVerifier $verifier,
        private string $header = 'X-Signature',
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();
        if (!str_starts_with($path, '/webhooks/') && !str_starts_with($path, '/api/webhooks/')) {
            return;
        }

        $signature = $request->headers->get($this->header);
        $payload = $request->getContent() ?: '';
        if (!$this->verifier->isValid($payload, $signature)) {
            $event->setResponse(new JsonResponse([
                'error' => 'Invalid webhook signature',
            ], 401));
        }
    }
}
