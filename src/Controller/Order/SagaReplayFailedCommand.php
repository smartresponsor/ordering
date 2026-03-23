<?php

declare(strict_types=1);

namespace App\Controller\Order;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\Doctrine\DoctrineReceiver;

#[AsCommand(name: 'order:saga:replay-failed', description: 'Replay failed messages from failure transport')]
final class SagaReplayFailedCommand extends Command
{
    public function __construct(private readonly DoctrineReceiver $failedReceiver, private readonly MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = 0;
        while (null !== ($envelope = $this->failedReceiver->get())) {
            /* @var Envelope $envelope */
            $this->bus->dispatch($envelope->getMessage());
            $this->failedReceiver->reject($envelope); // remove from failed after re-dispatch
            ++$count;
        }
        $output->writeln('Re-dispatched ' + $count + ' failed messages');

        return Command::SUCCESS;
    }
}
