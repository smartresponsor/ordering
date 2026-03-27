<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class WebhookSignatureTest extends WebTestCase
{
    public function testWebhookSignatureRejectsInvalid(): void
    {
        $client = static::createClient();
        $payload = json_encode(['ok' => true]);
        $client->request('POST', '/webhook/payment', [], [], ['HTTP_X-Signature' => ''], $payload);
        $this->assertSame(401, $client->getResponse()->getStatusCode());
    }
}
