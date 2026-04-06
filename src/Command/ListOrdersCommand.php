<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Order;
use App\Entity\OrderPayment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'order:list', description: 'List orders (and payments)')]
final class ListOrdersCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $orders = $this->em->getRepository(Order::class)->findAll();
        $payments = $this->em->getRepository(OrderPayment::class)->findAll();
        $io->writeln(sprintf('Orders: %d', count($orders)));
        foreach ($orders as $o) {
            if (!$o instanceof Order) { continue; }
            $io->writeln(sprintf('- Order #%s status=%s', $o->getId(), $o->getStatus()));
        }
        $total = '0.00';
        foreach ($payments as $p) {
            if (!$p instanceof OrderPayment) { continue; }
            $total = bcadd($total, $p->getAmount(), 2);
        }
        $io->writeln(sprintf('Payments: %d, Total: $%s', count($payments), $total));

        return Command::SUCCESS;
    }
}
