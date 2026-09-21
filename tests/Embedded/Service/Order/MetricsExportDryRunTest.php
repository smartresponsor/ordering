<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class MetricsExportDryRunTest extends KernelTestCase
{
    public function testExportCommandContractIsWired(): void
    {
        self::bootKernel();
        $kernel = self::$kernel;
        self::assertNotNull($kernel);

        $application = new Application($kernel);
        $command = $application->find('order:metrics:export');
        $definition = $command->getDefinition();

        self::assertTrue($definition->hasOption('since'));
        self::assertTrue($definition->hasOption('until'));
        self::assertTrue($definition->hasOption('sink'));
        self::assertTrue($definition->hasOption('batch'));
        self::assertTrue($definition->hasOption('dry-run'));
        self::assertStringNotContainsString('placeholder', (string) $command->getDescription());
    }
}
