<?php

declare(strict_types=1);

namespace Tests\Panther;

use Symfony\Component\Panther\PantherTestCase;

final class OrderManagementPantherTest extends PantherTestCase
{
    public function testManagementPageRenders(): void
    {
        $client = static::createPantherClient();
        $client->request('GET', '/manage/orders');

        self::assertSelectorTextContains('body', 'Ordering Demo');
    }
}
