<?php

declare(strict_types=1);

namespace App\Controller\Order;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:metrics:retention', description: 'Purge old analytics rows by updated_at (TTL)')]
final class OrderMetricsRetentionCommand extends Command
{
    public function __construct(private readonly Connection $db)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('table', null, InputOption::VALUE_REQUIRED, 'Table to purge', 'order_metrics_aggregate_view')
            ->addOption('older-than', null, InputOption::VALUE_REQUIRED, 'e.g. 180 days', '180 days')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Print SQL only');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = (string) $input->getOption('table');
        $older = new \DateTimeImmutable('now - '.(string) $input->getOption('older-than'));
        $sql = sprintf('DELETE FROM %s WHERE updated_at < :cutoff', $table);

        if ($input->getOption('dry-run')) {
            $output->writeln('[DRY-RUN] '.$sql.'; cutoff='.$older->format('Y-m-d H:i:s'));

            return Command::SUCCESS;
        }

        $aff = $this->db->executeStatement($sql, ['cutoff' => $older->format('Y-m-d H:i:s')]);
        $output->writeln(sprintf('<info>Purged %d rows from %s</info>', $aff, $table));

        return Command::SUCCESS;
    }
}
