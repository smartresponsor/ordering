<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Projector\Order\OrderMetricsProjector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:readmodel:sync-metrics', description: 'Rebuild OrderMetrics projection for a given day (YYYY-MM-DD)')]
class SyncOrderMetricsCommand extends Command
{
    public function __construct(private OrderMetricsProjector $projector)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('day', InputArgument::REQUIRED, 'Day in YYYY-MM-DD');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $day = new \DateTimeImmutable($input->getArgument('day'));
        $n = $this->projector->rebuildForDay($day);
        $output->writeln(sprintf('<info>Rebuilt %d metric rows for %s</info>', $n, $day->format('Y-m-d')));

        return Command::SUCCESS;
    }
}
