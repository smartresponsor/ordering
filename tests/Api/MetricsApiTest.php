<?php

declare(strict_types=1);

namespace Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MetricsApiTest extends WebTestCase
{
    public function testMetricsEndpointAvailable(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/metrics/orders');
        $this->assertTrue(in_array($client->getResponse()->getStatusCode(), [200, 401, 403], true));
    }
}
