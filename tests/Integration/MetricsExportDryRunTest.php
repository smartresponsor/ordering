<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Ordering\Command\OrderMetricsExportCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class MetricsExportDryRunTest extends KernelTestCase
{
    public function testExportDryRun(): void
    {
        self::bootKernel();
        $cmd = self::$kernel->getContainer()->get(OrderMetricsExportCommand::class);
        $tester = new CommandTester($cmd);
        $tester->execute([]);
        $this->assertStringContainsString('not wired yet', $tester->getDisplay());
    }
}
