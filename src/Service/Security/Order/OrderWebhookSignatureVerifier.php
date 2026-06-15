<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\Service\Webhook\Order\WebhookVerifierHmac;

final readonly class OrderWebhookSignatureVerifier
{
    public function __construct(
        private string $secret,
        private WebhookVerifierHmac $verifier = new WebhookVerifierHmac(),
    ) {
    }

    public function isValid(string $payload, ?string $signature): bool
    {
        if (null === $signature || '' === trim($signature)) {
            return false;
        }

        return $this->verifier->verify($payload, $this->secret, $signature);
    }
}
