<?php

declare(strict_types=1);

namespace App\Ordering\Command;

use App\Ordering\ServiceInterface\Analytics\Order\BigQueryExportSinkInterface;
use App\Ordering\ServiceInterface\Analytics\Order\OrderMetricsQueryServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:metrics:export', description: 'Export daily order metrics to a configured analytics sink')]
final class OrderMetricsExportCommand extends Command
{
    private const int MAX_BATCH_SIZE = 10000;

    public function __construct(
        private readonly OrderMetricsQueryServiceInterface $metrics,
        private readonly BigQueryExportSinkInterface $bigQuery,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('since', null, InputOption::VALUE_REQUIRED, 'Start date/time accepted by DateTimeImmutable', '-7 days')
            ->addOption('until', null, InputOption::VALUE_REQUIRED, 'End date/time accepted by DateTimeImmutable', 'now')
            ->addOption('sink', null, InputOption::VALUE_REQUIRED, 'Export sink: bigquery', 'bigquery')
            ->addOption('batch', null, InputOption::VALUE_REQUIRED, 'Rows per sink push', '1000')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Read and validate metrics without writing to the sink');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sinkOption = $input->getOption('sink');
        if (!is_string($sinkOption)) {
            $output->writeln('<error>The --sink option must be a string.</error>');

            return Command::INVALID;
        }

        $sinkName = strtolower(trim($sinkOption));
        if ('bigquery' !== $sinkName) {
            $output->writeln(sprintf('<error>Unsupported metrics export sink "%s". Use bigquery.</error>', $sinkName));

            return Command::INVALID;
        }

        $sink = $this->bigQuery;

        $batchOption = $input->getOption('batch');
        if (!is_int($batchOption) && !is_string($batchOption)) {
            $output->writeln('<error>The --batch option must be an integer.</error>');

            return Command::INVALID;
        }

        $batchSize = filter_var($batchOption, FILTER_VALIDATE_INT);
        if (false === $batchSize || $batchSize < 1 || $batchSize > self::MAX_BATCH_SIZE) {
            $output->writeln(sprintf('<error>Batch size must be an integer between 1 and %d.</error>', self::MAX_BATCH_SIZE));

            return Command::INVALID;
        }

        $sinceOption = $input->getOption('since');
        $untilOption = $input->getOption('until');
        if (!is_string($sinceOption) || !is_string($untilOption)) {
            $output->writeln('<error>The --since and --until options must be strings.</error>');

            return Command::INVALID;
        }

        try {
            $from = new \DateTimeImmutable($sinceOption);
            $to = new \DateTimeImmutable($untilOption);
        } catch (\Exception $exception) {
            $output->writeln('<error>Invalid metrics export date: '.$exception->getMessage().'</error>');

            return Command::INVALID;
        }

        if ($from > $to) {
            $output->writeln('<error>The --since date must not be later than --until.</error>');

            return Command::INVALID;
        }

        $rows = $this->metrics->ordersByDay($from, $to);
        $count = count($rows);

        if ((bool) $input->getOption('dry-run')) {
            $output->writeln(sprintf(
                '<info>Dry run: %d metric row(s) ready for %s from %s through %s.</info>',
                $count,
                $sinkName,
                $from->format('Y-m-d'),
                $to->format('Y-m-d'),
            ));

            return Command::SUCCESS;
        }

        foreach (array_chunk($rows, $batchSize) as $batch) {
            $sink->push($batch);
        }
        $sink->flush();

        $output->writeln(sprintf('<info>Exported %d metric row(s) to %s.</info>', $count, $sinkName));

        return Command::SUCCESS;
    }
}
