<?php

declare(strict_types=1);

namespace App\Command\Outbox;

use App\Processor\Outbox\OrderOutboxProcessor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'order:outbox:run', description: 'Process order outbox records.')]
final class OrderOutboxRunCommand extends Command
{
    public function __construct(private readonly OrderOutboxProcessor $processor)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $processed = $this->processor->run();
        $io->success(sprintf('Processed %d outbox records.', $processed));

        return Command::SUCCESS;
    }
}
