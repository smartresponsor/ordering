<?php

declare(strict_types=1);

namespace Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrderApiTest extends WebTestCase
{
    public function testOrdersCollectionIsExposed(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/orders');
        $this->assertTrue(in_array($client->getResponse()->getStatusCode(), [200, 401, 403], true));
    }
}
