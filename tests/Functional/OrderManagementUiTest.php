<?php

declare(strict_types=1);

namespace Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrderManagementUiTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $tool = new SchemaTool($em);
        $tool->dropSchema($em->getMetadataFactory()->getAllMetadata());
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());
        $this->client->disableReboot();
    }

    public function testManagementPageAllowsCreateAndPaymentFlow(): void
    {
        $crawler = $this->client->request('GET', '/manage/orders');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Create demo order');

        $this->client->submitForm('Create order', [
            'order_create[currency]' => 'USD',
            'order_create[grandTotal]' => '99.99',
        ]);

        self::assertResponseRedirects('/manage/orders');
        $crawler = $this->client->followRedirect();
        self::assertSelectorTextContains('.alert-success', 'created');

        $orderId = $crawler->filter('.fw-semibold')->first()->text();
        $this->client->submitForm('Capture payment', [
            'order_payment[amount]' => '99.99',
            'order_payment[externalRef]' => 'ui-pay-1',
        ]);

        self::assertResponseRedirects('/manage/orders');
        $crawler = $this->client->followRedirect();
        self::assertStringContainsString($orderId, $crawler->filter('.fw-semibold')->first()->text());
        self::assertStringContainsString('PAID', strtoupper($crawler->filter('.text-muted.small')->first()->text()));
    }
}
