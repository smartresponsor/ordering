<?php

declare(strict_types=1);

namespace App\Command\Outbox;

use App\Service\Outbox\OutboxMessengerDispatcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

#[AsCommand(name: 'order:outbox:dispatch', description: 'Send pending outbox events to Messenger transport')]
final class OrderOutboxDispatchCommand extends Command
{
    public function __construct(private readonly OutboxMessengerDispatcher $disp)
    {
        parent::__construct();
    }

    /**
     * @throws \JsonException
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $n = $this->disp->dispatchPending();
        $io->success("Dispatched $n messages");

        return Command::SUCCESS;
    }
}
