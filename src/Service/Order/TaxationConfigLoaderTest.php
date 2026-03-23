<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Service\Order\Pricing\TaxationConfigLoader;
use PHPUnit\Framework\TestCase;

final class TaxationConfigLoaderTest extends TestCase
{
    public function testLoaderReadsYaml(): void
    {
        $loader = new TaxationConfigLoader(__DIR__.'/../../../config/taxation.yaml');
        $this->assertSame(2, $loader->rounding());
        $rate = $loader->rateFor('EU', 'DE');
        $this->assertSame('19', (string) $rate);
    }
}
