<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Ordering\Service\Pricing\Order\TaxationConfigLoader;
use PHPUnit\Framework\TestCase;

final class TaxationConfigLoaderTest extends TestCase
{
    public function testLoaderReadsYaml(): void
    {
        $loader = new TaxationConfigLoader(dirname(__DIR__, 4).'/config/taxation.yaml');
        $this->assertSame(2, $loader->rounding());
        $rate = $loader->rateFor('EU', 'DE');
        $this->assertSame('19', (string) $rate);
    }
}
