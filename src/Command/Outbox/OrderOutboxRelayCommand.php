<?php

declare(strict_types=1);

namespace App\Command\Outbox;

use App\ServiceInterface\Outbox\Order\OutboxRelayInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:outbox:relay', description: 'Dispatch messages from outbox to Messenger')]
final class OrderOutboxRelayCommand extends Command
{
    public function __construct(private readonly OutboxRelayInterface $relay)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $processed = $this->relay->runOnce(100);
        $output->writeln(sprintf('<info>Relayed: %d</info>', $processed));

        return Command::SUCCESS;
    }
}
