<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Service\Order\OrderArchivalService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:archive:old', description: 'Archive old orders')]
final class OrderArchiveOldCommand extends Command
{
    public function __construct(private readonly OrderArchivalService $archival)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('months', InputArgument::OPTIONAL, 'Older than N months', '12');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $months = (int) $input->getArgument('months');
        $count = $this->archival->archiveOlderThan($months);
        $output->writeln(sprintf('Archived %d orders older than %d months', $count, $months));

        return Command::SUCCESS;
    }
}
