<?php

declare(strict_types=1);

namespace Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrderMetricsRollupTest extends WebTestCase
{
    public function testRollupEndpointWorks(): void
    {
        $client = static::createClient();
        $vendorId = 'VND-001';
        $client->request('GET', "/vendors/{$vendorId}/metrics/rollup?period=quarter");
        $this->assertTrue($client->getResponse()->isSuccessful());
        $data = json_decode($client->getResponse()->getContent() ?: '[]', true);
        $this->assertIsArray($data);
    }
}
