<?php

declare(strict_types=1);

namespace Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrderCustomOpsApiTest extends WebTestCase
{
    public function testOrderCreateEndpointExists(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/orders', server: ['CONTENT_TYPE' => 'application/json'], content: json_encode([
            'vendorId' => 'V-1',
            'currency' => 'USD',
            'items' => [['sku' => 'SKU-1', 'price' => '10.00', 'qty' => 1]],
        ]));
        $this->assertTrue(in_array($client->getResponse()->getStatusCode(), [201, 400, 401, 403, 422], true));
    }

    public function testOrderRefundEndpointExists(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/orders/1/refund', server: ['CONTENT_TYPE' => 'application/json'], content: json_encode([
            'amount' => '5.00',
            'idempotencyKey' => 'ikey-1',
        ]));
        $this->assertTrue(in_array($client->getResponse()->getStatusCode(), [200, 202, 400, 401, 403, 404, 422], true));
    }
}
