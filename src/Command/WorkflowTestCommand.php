<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Order\OrderEntity;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'order:workflow:test', description: 'Simulate basic order workflow transitions')]
final class WorkflowTestCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $order = new OrderEntity('USD', '10.00');
        $order->applyPayment('10.00', 'demo-payment');
        $order->ship('UPS', 'TRACK-DEMO', 'workflow test');

        $io->success(sprintf('Workflow simulated for %s: %s', $order->slug(), $order->status()));

        return Command::SUCCESS;
    }
}
