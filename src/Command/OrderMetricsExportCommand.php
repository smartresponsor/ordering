<?php

declare(strict_types=1);

namespace App\Ordering\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:metrics:export', description: 'Export order metrics placeholder output')]
final class OrderMetricsExportCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Order metrics export is not wired yet.</info>');

        return Command::SUCCESS;
    }
}
