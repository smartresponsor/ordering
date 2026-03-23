<?php

declare(strict_types=1);

namespace App\ReadModel\Command;

use App\ReadModel\Service\OrderReadModelProjector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:read-model:sync-all', description: 'Sync order read models that are already stored.')]
final class SyncReadModelsCommand extends Command
{
    public function __construct(private readonly OrderReadModelProjector $projector)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Read-model sync bootstrap is available.</info>');

        return Command::SUCCESS;
    }
}
