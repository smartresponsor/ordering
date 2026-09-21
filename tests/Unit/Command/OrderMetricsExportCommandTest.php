<?php

declare(strict_types=1);

namespace App\Ordering\Tests\Unit\Command;

use App\Ordering\Command\OrderMetricsExportCommand;
use App\Ordering\ServiceInterface\Analytics\Order\BigQueryExportSinkInterface;
use App\Ordering\ServiceInterface\Analytics\Order\OrderMetricsQueryServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class OrderMetricsExportCommandTest extends TestCase
{
    public function testExportsRowsInConfiguredBatches(): void
    {
        $rows = [
            ['date' => '2026-09-01', 'orders' => 2, 'gross' => '20.00', 'refund' => '0.00', 'net' => '20.00'],
            ['date' => '2026-09-02', 'orders' => 3, 'gross' => '30.00', 'refund' => '5.00', 'net' => '25.00'],
            ['date' => '2026-09-03', 'orders' => 1, 'gross' => '10.00', 'refund' => '0.00', 'net' => '10.00'],
        ];

        $metrics = $this->createMock(OrderMetricsQueryServiceInterface::class);
        $metrics->expects(self::once())->method('ordersByDay')->willReturn($rows);

        $batches = [];
        $bigQuery = $this->createMock(BigQueryExportSinkInterface::class);
        $bigQuery->expects(self::exactly(2))
            ->method('push')
            ->willReturnCallback(static function (array $batch) use (&$batches): void {
                $batches[] = $batch;
            });
        $bigQuery->expects(self::once())->method('flush');

        $tester = new CommandTester(new OrderMetricsExportCommand($metrics, $bigQuery));
        $status = $tester->execute([
            '--since' => '2026-09-01',
            '--until' => '2026-09-03',
            '--sink' => 'bigquery',
            '--batch' => '2',
        ]);

        self::assertSame(Command::SUCCESS, $status);
        self::assertSame([array_slice($rows, 0, 2), array_slice($rows, 2)], $batches);
        self::assertStringContainsString('Exported 3 metric row(s) to bigquery.', $tester->getDisplay());
    }

    public function testDryRunReadsMetricsWithoutWritingSink(): void
    {
        $metrics = $this->createMock(OrderMetricsQueryServiceInterface::class);
        $metrics->expects(self::once())->method('ordersByDay')->willReturn([]);

        $bigQuery = $this->createMock(BigQueryExportSinkInterface::class);
        $bigQuery->expects(self::never())->method('push');
        $bigQuery->expects(self::never())->method('flush');

        $tester = new CommandTester(new OrderMetricsExportCommand($metrics, $bigQuery));
        $status = $tester->execute([
            '--since' => '2026-09-01',
            '--until' => '2026-09-03',
            '--sink' => 'bigquery',
            '--dry-run' => true,
        ]);

        self::assertSame(Command::SUCCESS, $status);
        self::assertStringContainsString('Dry run: 0 metric row(s) ready for bigquery', $tester->getDisplay());
    }

    public function testRejectsUnsupportedSinkBeforeQueryingMetrics(): void
    {
        $metrics = $this->createMock(OrderMetricsQueryServiceInterface::class);
        $metrics->expects(self::never())->method('ordersByDay');

        $bigQuery = $this->createMock(BigQueryExportSinkInterface::class);

        $tester = new CommandTester(new OrderMetricsExportCommand($metrics, $bigQuery));
        $status = $tester->execute(['--sink' => 'clickhouse']);

        self::assertSame(Command::INVALID, $status);
        self::assertStringContainsString('Unsupported metrics export sink', $tester->getDisplay());
    }
}
