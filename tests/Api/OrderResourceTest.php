<?php

declare(strict_types=1);

namespace Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrderResourceTest extends WebTestCase
{
    public function testCreateAndFlow(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/orders',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(['currency' => 'USD', 'grandTotal' => '100.00']),
        );
        self::assertResponseStatusCodeSame(201);

        $id = json_decode($client->getResponse()->getContent(), true)['id'];
        $client->request(
            'POST',
            "/orders/$id/pay",
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(['amount' => '40.00', 'externalRef' => 'api']),
        );
        self::assertResponseIsSuccessful();
    }
}
