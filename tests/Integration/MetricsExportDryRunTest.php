<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Command\OrderMetricsExportCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class MetricsExportDryRunTest extends KernelTestCase
{
    public function testExportDryRun(): void
    {
        self::bootKernel();
        $app = new Application(self::$kernel);
        $cmd = self::$kernel->getContainer()->get(OrderMetricsExportCommand::class);
        $app->add($cmd);
        $tester = new CommandTester($app->find('app:metrics:export'));
        $tester->execute(['--dry-run' => true, '--since' => '-1 day', '--sink' => 'bigquery', '--batch' => '10']);
        $this->assertStringContainsString('Export finished', $tester->getDisplay());
    }
}
