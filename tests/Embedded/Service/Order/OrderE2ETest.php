<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrderE2ETest extends WebTestCase
{
    public function testFullFlowRealServices(): void
    {
        $client = static::createClient();
        $report = dirname(__DIR__, 2).'/var/log/e2e_report.log';

        // 1) Create order (API Platform should expose /orders)
        $payload = json_encode(['status' => 'draft', 'currency' => 'USD', 'total' => '199.00'], JSON_THROW_ON_ERROR);
        $client->request('POST', '/orders', server: ['CONTENT_TYPE' => 'application/json'], content: $payload);
        Assert::assertTrue($client->getResponse()->isSuccessful(), 'Create order failed');
        $content = $client->getResponse()->getContent();
        Assert::assertNotFalse($content, 'Create order response missing');
        /** @var array{id?: string, '@id'?: string} $data */
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        $id = $data['id'] ?? $data['@id'] ?? null;
        Assert::assertNotEmpty($id, 'Order id missing');

        file_put_contents($report, "Created order: {$id}\n", FILE_APPEND);

        // Normalize ID if IRI
        if (is_string($id) && str_starts_with($id, '/')) {
            $parts = explode('/', trim($id, '/'));
            $id = end($parts);
        }
        Assert::assertIsString($id, 'Order id must resolve to a string');

        // 2) Pay
        $client->request('POST', "/orders/{$id}/pay");
        Assert::assertTrue($client->getResponse()->isSuccessful(), 'Pay failed');
        file_put_contents($report, "Pay request sent for {$id}\n", FILE_APPEND);

        // 3) Ship
        $client->request('POST', "/orders/{$id}/ship");
        Assert::assertTrue($client->getResponse()->isSuccessful(), 'Ship failed');
        file_put_contents($report, "Ship request sent for {$id}\n", FILE_APPEND);

        // 4) Complete
        $client->request('POST', "/orders/{$id}/complete");
        Assert::assertTrue($client->getResponse()->isSuccessful(), 'Complete failed');
        file_put_contents($report, "Complete request sent for {$id}\n", FILE_APPEND);

        // Optionally fetch entity to assert status (requires a GET endpoint to reflect final status)
        $client->request('GET', "/orders/{$id}");
        Assert::assertTrue($client->getResponse()->isSuccessful(), 'Fetch order failed');
        $finalContent = $client->getResponse()->getContent();
        Assert::assertNotFalse($finalContent, 'Fetch order response missing');
        /** @var array<string, mixed> $final */
        $final = json_decode($finalContent, true, 512, JSON_THROW_ON_ERROR);
        file_put_contents($report, 'Final order payload: '.json_encode($final)."\n", FILE_APPEND);
    }
}
