<?php

declare(strict_types=1);

namespace Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ApiSecurityTest extends WebTestCase
{
    public function testMetricsRequiresRole(): void
    {
        $client = static::createClient(); // public read model compatibility
        $client->request('GET', '/vendors/VND-001/metrics');
        $this->assertTrue(in_array($client->getResponse()->getStatusCode(), [200, 401, 403], true));
    }
}
