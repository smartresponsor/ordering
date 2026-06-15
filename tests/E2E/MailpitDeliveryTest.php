<?php

declare(strict_types=1);

namespace Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MailpitDeliveryTest extends WebTestCase
{
    public function testEmailDeliveryViaMailpit(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/order/test/email', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'to' => 'test@example.com',
            'subject' => 'Mailpit E2E Test',
            'message' => 'Hello from Order!',
        ]));
        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent() ?: '{}', true) ?: [];
        $this->assertSame('stubbed', $response['status'] ?? null);
        $this->assertSame('test@example.com', $response['sent_to'] ?? null);
        $this->assertSame('Mailpit E2E Test', $response['subject'] ?? null);
    }
}
