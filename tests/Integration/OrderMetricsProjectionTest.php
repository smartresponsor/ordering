<?php

declare(strict_types=1);

namespace Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrderMetricsProjectionTest extends WebTestCase
{
    public function testMetricsEndpointReturnsView(): void
    {
        $client = static::createClient();
        $vendorId = 'VND-001';

        // Call the endpoint (should return null or default until events processed)
        $client->request('GET', '/vendors/'.$vendorId.'/metrics');
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Metrics endpoint should respond');

        // Optionally assert on structure
        $json = json_decode($client->getResponse()->getContent() ?: '{}', true);
        $this->assertIsArray($json);
    }
}
