<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ApiSecurityTest extends WebTestCase
{
    public function testMetricsRequiresRole(): void
    {
        $client = static::createClient(); // no auth -> expect 403 from API Platform security
        $client->request('GET', '/vendors/VND-001/metrics');
        $this->assertTrue(in_array($client->getResponse()->getStatusCode(), [401, 403], true));
    }
}
