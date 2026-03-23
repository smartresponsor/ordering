<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Service\Order\BigQueryExportSink;
use App\Service\Order\ClickHouseExportSink;
use App\Service\Order\ExportSinkInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:metrics:export', description: 'Export order metrics to external DWH (ClickHouse/BigQuery)')]
final class OrderMetricsExportCommand extends Command
{
    public function __construct(private readonly Connection $db)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('since', null, InputOption::VALUE_OPTIONAL, 'Start timestamp or relative time', '-7 days')
            ->addOption('sink', null, InputOption::VALUE_REQUIRED, 'clickhouse|bigquery', 'clickhouse')
            ->addOption('batch', null, InputOption::VALUE_OPTIONAL, 'Batch size', '1000')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Do not persist export log');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $since = (string) $input->getOption('since');
        $sinceAt = new \DateTimeImmutable($since);
        $sinkName = (string) $input->getOption('sink');
        $batchSize = (int) $input->getOption('batch');
        $dry = (bool) $input->getOption('dry-run');

        $sink = $this->makeSink($sinkName);
        $hash = sha1($sinkName.'|'.$sinceAt->format(DATE_ATOM));

        // idempotency check
        $exists = $this->db->fetchOne('SELECT 1 FROM metrics_export_log WHERE hash = ?', [$hash]);
        if ($exists && !$dry) {
            $output->writeln('<comment>Already exported for this window/sink. Skipping.</comment>');

            return Command::SUCCESS;
        }

        $sql = 'SELECT vendor_id, period_type, period_value, total_orders, total_revenue, refunded_amount, ltv, updated_at
                FROM order_metrics_aggregate_view WHERE updated_at >= :since';
        $stmt = $this->db->prepare($sql);
        $res = $stmt->executeQuery(['since' => $sinceAt->format('Y-m-d H:i:s')]);

        $batch = [];
        while ($row = $res->fetchAssociative()) {
            $batch[] = $row;
            if (count($batch) >= $batchSize) {
                $sink->push($batch);
                $batch = [];
            }
        }
        if ($batch) {
            $sink->push($batch);
        }
        $sink->flush();

        if (!$dry) {
            $this->db->insert('metrics_export_log', [
                'hash' => $hash,
                'sink' => $sinkName,
                'since_at' => $sinceAt->format('Y-m-d H:i:s'),
                'created_at' => (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s'),
            ]);
        }

        $output->writeln('<info>Export finished.</info>');

        return Command::SUCCESS;
    }

    private function makeSink(string $name): ExportSinkInterface
    {
        return match ($name) {
            'clickhouse' => new ClickHouseExportSink(getenv('CLICKHOUSE_ENDPOINT') ?: 'http://localhost:8123'),
            'bigquery' => new BigQueryExportSink(getenv('BIGQUERY_OUT') ?: sys_get_temp_dir()),
            default => throw new \InvalidArgumentException('Unsupported sink: '.$name),
        };
    }
}
