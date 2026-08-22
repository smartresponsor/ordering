<?php

declare(strict_types=1);

namespace App\Test\Smoke;

use App\Ordering\Service\Webhook\Order\WebhookSignerHmac;
use App\Ordering\Service\Webhook\Order\WebhookVerifierHmac;
use PHPUnit\Framework\TestCase;

/**
 * Basic smoke coverage for webhook signing and verification.
 */
final class WebhookHmacTest extends TestCase
{
    public function testSignVerify(): void
    {
        $signer = new WebhookSignerHmac();
        $verifier = new WebhookVerifierHmac();
        $payload = '{"ok":true}';
        $secret = 'x';
        $signature = $signer->sign($payload, $secret);

        self::assertTrue($verifier->verify($payload, $secret, $signature));
        self::assertFalse($verifier->verify($payload, 'y', $signature));
    }
}
