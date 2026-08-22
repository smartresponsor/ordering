<?php

declare(strict_types=1);

namespace App\Test\Smoke;

use App\Ordering\Service\Webhook\Order\WebhookSignerRsa;
use App\Ordering\Service\Webhook\Order\WebhookVerifierRsa;
use PHPUnit\Framework\TestCase;

/**
 * Basic smoke coverage for webhook signing and verification.
 */
final class WebhookRsaTest extends TestCase
{
    public function testSignVerify(): void
    {
        $cfg = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
        $resource = openssl_pkey_new($cfg);
        openssl_pkey_export($resource, $privateKey);
        $details = openssl_pkey_get_details($resource);
        $publicKey = $details['key'];

        $signer = new WebhookSignerRsa();
        $verifier = new WebhookVerifierRsa();
        $payload = '{"ok":true}';

        $envelope = $signer->sign($payload, $privateKey, 'kid-test');
        self::assertTrue($verifier->verify($payload, $publicKey, $envelope));
    }
}
