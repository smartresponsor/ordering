<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrderAnalyticsLtvTest extends WebTestCase
{
    public function testAggregateEndpointReturnsCollection(): void
    {
        $client = static::createClient();
        $vendorId = 'VND-001';
        $client->request('GET', "/vendors/{$vendorId}/metrics/aggregate?period=month");
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Aggregate endpoint should respond');
        $json = json_decode($client->getResponse()->getContent() ?: '[]', true);
        $this->assertIsArray($json);
    }
}
