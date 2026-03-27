<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrderE2ETest extends WebTestCase
{
    private static function dbDsnParts(string $url): array
    {
        // postgres://user:pass@host:5432/db
        $parts = parse_url($url);

        return [
            'host' => $parts['host'] ?? 'localhost',
            'port' => $parts['port'] ?? 5432,
            'user' => $parts['user'] ?? 'app',
            'pass' => $parts['pass'] ?? 'app',
            'db' => ltrim($parts['path'] ?? '/app', '/'),
        ];
    }

    public static function setUpBeforeClass(): void
    {
        $proj = dirname(__DIR__, 2);
        $sqlFile = $proj.'/tests/E2E/sql/init_orders.sql';
        if (file_exists($sqlFile) && ($url = getenv('DATABASE_URL'))) {
            $p = self::dbDsnParts($url);
            $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s', $p['host'], $p['port'], $p['db']);
            try {
                $pdo = new \PDO($dsn, $p['user'], $p['pass']);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $pdo->exec(file_get_contents($sqlFile));
            } catch (\Throwable $e) {
                // ignore seeding errors but log
                file_put_contents($proj.'/var/log/e2e_report.log', 'Seed error: '.$e->getMessage()."\n", FILE_APPEND);
            }
        }
    }

    public function testFullFlowRealServices(): void
    {
        $client = static::createClient();
        $report = dirname(__DIR__, 2).'/var/log/e2e_report.log';

        // 1) Create order (API Platform should expose /orders)
        $payload = json_encode(['status' => 'draft', 'currency' => 'USD', 'total' => '199.00'], JSON_THROW_ON_ERROR);
        $client->request('POST', '/orders', server: ['CONTENT_TYPE' => 'application/json'], content: $payload);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Create order failed');
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $id = $data['id'] ?? $data['@id'] ?? null;
        $this->assertNotEmpty($id, 'Order id missing');

        file_put_contents($report, "Created order: {$id}\n", FILE_APPEND);

        // Normalize ID if IRI
        if (is_string($id) && str_starts_with($id, '/')) {
            $parts = explode('/', trim($id, '/'));
            $id = end($parts);
        }

        // 2) Pay
        $client->request('POST', "/orders/{$id}/pay");
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Pay failed');
        file_put_contents($report, "Pay request sent for {$id}\n", FILE_APPEND);

        // 3) Ship
        $client->request('POST', "/orders/{$id}/ship");
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Ship failed');
        file_put_contents($report, "Ship request sent for {$id}\n", FILE_APPEND);

        // 4) Complete
        $client->request('POST', "/orders/{$id}/complete");
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Complete failed');
        file_put_contents($report, "Complete request sent for {$id}\n", FILE_APPEND);

        // Optionally fetch entity to assert status (requires a GET endpoint to reflect final status)
        $client->request('GET', "/orders/{$id}");
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Fetch order failed');
        $final = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        file_put_contents($report, 'Final order payload: '.json_encode($final)."\n", FILE_APPEND);
    }
}
