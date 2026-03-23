<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Message\Command\StartOrderSagaCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'order:outbox:dispatch', description: 'Dispatch outbox events into Messenger bus')]
final class OutboxDispatchCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rows = $this->em->getConnection()->fetchAllAssociative('SELECT * FROM outbox_message ORDER BY occurred_at ASC LIMIT 500');
        foreach ($rows as $row) {
            // demo: route all to StartOrderSaga (replace with mapping by $row['topic'])
            $this->bus->dispatch(new StartOrderSagaCommand((int) ($row['payload']['orderId'] ?? 0)));
        }
        $output->writeln('Dispatched '.count($rows).' outbox messages');

        return Command::SUCCESS;
    }
}
